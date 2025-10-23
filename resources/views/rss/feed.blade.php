<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
<channel>
  <title><![CDATA[Последние Новости Lumina]]></title>
  <link>{{ url('/articles') }}</link>
  <description><![CDATA[Последние статьи и события]]></description>
  <language>ru</language>
  <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
  <ttl>60</ttl>

  @foreach($articles as $item)
    <item>
      <title><![CDATA[{{ $item->title }}]]></title>
      <link>{{ url('/articles/'.$item->slug) }}</link>
      <description><![CDATA[{{ $item->excerpt ?? $item->content }}]]></description>
      <pubDate>{{ $item->created_at->toRfc2822String() }}</pubDate>
      <guid>{{ url('/articles/'.$item->slug) }}</guid>
    </item>
  @endforeach

</channel>
</rss>
