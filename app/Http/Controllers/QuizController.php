<?php

namespace App\Http\Controllers;

    use App\Models\Diploma;
    use App\Models\Question;
    use App\Models\Subject;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;

    class QuizController extends Controller
    {
  public function getQuestions(Request $request)
    {
        $subjectId = $request->query('subject_id');

        if (!$subjectId) {
            return response()->json(['error' => 'Subject ID is required'], 400);
        }

        $limit = $request->query('limit', 20); // по умолчанию 20

        $questions = Question::where('subject_id', $subjectId)
            ->inRandomOrder()
            ->limit($limit)
            ->get()
            ->map(function ($question) {
                $answers = [
                    ['text' => $question->correct_answer, 'is_correct' => true],
                    ['text' => $question->wrong_answer_1, 'is_correct' => false],
                    ['text' => $question->wrong_answer_2, 'is_correct' => false],
                    ['text' => $question->wrong_answer_3, 'is_correct' => false],
                ];

                shuffle($answers);

                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'answers' => $answers,
                ];
            });

        return response()->json($questions);
    }
        public function submitQuiz(Request $request)
    {
        try {
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'answers' => 'required|array',
                'subject_id' => 'required|integer|exists:subjects,id',
            ]);

            $firstname = $request->input('firstname');
            $lastname = $request->input('lastname');
            $answers = $request->input('answers');
            $subjectId = $request->input('subject_id');

            $calculatedScore = 0;

            foreach ($answers as $answer) {
                $question = Question::find($answer['question_id']);
                if (!$question) {
                    continue;
                }

                if ($question->correct_answer === $answer['answer']) {
                    $calculatedScore++;
                }
            }

            $documentNumber = str_pad(random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT);

            $diplomaBase64 = $this->generateDiploma([
                'firstname' => $firstname,
                'lastname' => $lastname
            ], $calculatedScore, $subjectId, $documentNumber);
            Diploma::create([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'score' => $calculatedScore,
                'document_number' => $documentNumber,
                'subject_id' => $subjectId,
            ]);

            return response()->json([
                'message' => 'Ваш сертификат готов!',
                'score' => $calculatedScore,
                'diploma_base64' => $diplomaBase64,
                'document_number' => $documentNumber,
            ]);

        } catch (\Exception $e) {
            Log::error('submitAnswers error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Ошибка сервера'], 500);
        }
    }
    public function generateDiploma($user, $score, $subjectId, $documentNumber)
    {
        try {
            $subject = Subject::find($subjectId);
            if (!$subject) {
                return null;
            }

            $subjectName = $subject->name;

            if ($score >= 10) {
                $templatePath = public_path('images/diploma1.jpg');
            } else {
                $templatePath = public_path('images/diploma.jpg');
            }

            $fontPath = 'C:\\fonts\\Manrope-Regular.ttf';
            // $fontPath = realpath(public_path('fonts/Manrope-Regular.ttf'));

            Log::info('Font path: ' . $fontPath);

            if (!file_exists($templatePath) || !file_exists($fontPath)) {
                Log::error('Missing template or font', ['template' => $templatePath, 'font' => $fontPath]);
                return null;
            }

            $image = imagecreatefromjpeg($templatePath);
            $black = imagecolorallocate($image, 57,57,57);

            imagettftext($image, 50, 0, 997, 815, $black, $fontPath, $user['lastname'] . ' ' . $user['firstname']);
            imagettftext($image, 40, 0, 1180, 1055, $black, $fontPath, $score);
            imagettftext($image, 30, 0, 250, 1540, $black, $fontPath, $subjectName);
            imagettftext($image, 30, 0, 250, 1610, $black, $fontPath, $documentNumber);

            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);

            return 'data:image/png;base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            Log::error('generateDiploma error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    }
