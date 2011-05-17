<?php

/*
 * Naoya Tozuka
 *
 * A random sentence generator
 */

# なんちゃって正規分布
function my_rand($lo, $hi)
{
  $mu = ($lo + $hi + 1)/2;
  $d = $hi - $lo;

  $x = 0; for ($i=6; $i>0; --$i) $x += rand($lo - $d, $hi + $d);
  $x = (int)(($x + 3)/6);

  if ($x < $lo) $x = $lo;
  elseif ($x > $hi) $x = $hi;

  return $x;
}

function p($true_rate)
{
  return (rand(1,100) <= $true_rate) ? true : false;
}

# 要素の入った配列から、なんちゃって分布に従ってランダムに１つ選んで返す
function my_choose($candidates, $useGaussian=true)
{
  if ($useGaussian) return $candidates[ my_rand(0, count($candidates)-1) ];
  else return $candidates[ rand(0, count($candidates)-1) ];
}  

# ランダムに文を生成
function generate_random_sentence($params, $useGaussian=true)
{
  $words_min = $params['words_in_a_sentence'][0];
  $words_max = $params['words_in_a_sentence'][1];

  $letters_min = $params['letters_in_a_word'][0];
  $letters_max = $params['letters_in_a_word'][1];

  $sentence = '';
  for ($i=my_rand($words_min, $words_max); $i>0; --$i)
  {
    $w = array();
    for ($j=my_rand($letters_min, $letters_max); $j>0; --$j) $w[] = my_choose($params['letters'], $useGaussian);

    $sentence .= join('',$w);
    if ($i>1) $sentence .= my_choose($params['word_separators'], $useGaussian);
    else $sentence .= my_choose($params['endings'], $useGaussian);
  }

  return $sentence;
}


/*
 * 利用例
 *
 */
function generate_tetete()
{
  $params = array(
	  'letters' => array('te'),
      'endings' => array('he!!', '!', '.', '?', '!?'),
      'word_separators' => array(' '),
	  'words_in_a_sentence' => array(1,6),
	  'letters_in_a_word' => array(1,7),
	);
  return generate_random_sentence($params);
}

function generate_tetete_ja()
{
  $params = array(
	  'letters' => array('て'),
      'endings' => array('へ☆', '！', '。', '？', 'へ＞＜'),
      'word_separators' => array('　'),
	  'words_in_a_sentence' => array(1,4),
	  'letters_in_a_word' => array(1,6),
	);
  return generate_random_sentence($params);
}

function generate_ebiebi()
{
  $params = array(
	  'letters' => array('こみみけ','えび','さそり','かに','はら','うばー','うべー'),
      'endings' => array('ー☆','だYO', 'やで', '＞＜'),
      'word_separators' => array('　'),
	  'words_in_a_sentence' => array(1,4),
	  'letters_in_a_word' => array(1,6),
	);
  return generate_random_sentence($params);
}

function generate_morus()
{
  $params = array(
	  'letters' => array('-','.',),
      'endings' => array(''),
      'word_separators' => array(' '),
	  'words_in_a_sentence' => array(1,12),
	  'letters_in_a_word' => array(1,5),
	);
  return generate_random_sentence($params);
}

function generate_sansuu()
{
  $params = array(
	  'letters' => array('1','2','3','4','5','6','7','8','9'),
      'endings' => array(' = '),
      'word_separators' => array('×', '＋', 'ー', '÷'),
	  'words_in_a_sentence' => array(3,6),
	  'letters_in_a_word' => array(1,5),
	);
  return generate_random_sentence($params);
}

function generate_melody()
{
  $params = array(
	  'letters' => array('ふぁ＃','し♭','ど','ら♭','れ','み','ふぁ','そ','ら','し','み♭','ど＃'),
      'endings' => array('♪'),
      'word_separators' => array('　'),
	  'words_in_a_sentence' => array(2,6),
	  'letters_in_a_word' => array(3,7),
	);
  return generate_random_sentence($params);
}

function generate_aizuchi($worse = false)
{
  $choix = array('ｶﾞﾀｯ', 'ですよねー', 'なるほどやでー', 'ふむふむ', 'へぇー', 'φ(｀д´)ﾒﾓﾒﾓ...', 'ふーん', 'ほほー', '＞＜');
  if ($worse)
  {
    $choix = array_merge(
      $choix, array(
        'すみません',
        'うわ'.str_repeat('あ', rand(0,3)),
        'ぷんぷくり～ん（怒）',
        'えー！ なにそれ!?　 知りたい知りたーい♪',
        '＼(^o^)／',
        '♡',
        'ﾃｨｳﾝﾃｨｳﾝ',
        ));
    if (p(50)) $choix[] = 'うそおつ';
    if (p(30)) $choix[] = 'm9(^Д^)ﾌﾟｷﾞｬｰ';
  }
  return my_choose($choix, false);
}
function generate_kuseni($name)
{
  return $name.'のくせに…';
}

function generate_marukame()
{
  $udon = array(
    '釜揚げうどん', '釜玉うどん', '明太釜玉うどん', 'ぶっかけうどん',
    'かけうどん', 'ざるうどん', 'おろし醤油うどん', 'とろ玉うどん', 'カレーうどん'
  );
  $mori = array('大', '並', '並', '梅', '笑');
  $order = my_choose($udon).'('.my_choose($mori,true).')';

  $tempura = generate_random_sentence(
    array(
      'letters' => array(
        '野菜かき揚げ', '半熟玉子天', 'かしわ天', 'えび天', 'いか天', 'げそ天',
        'きす天', 'ちくわ天', 'さつまいも天', 'なす天', 'かぼちゃ天',
      ),
      'endings' => array(''),
      'word_separators' => array(' + '),
      'words_in_a_sentence' => array(0,3),
      'letters_in_a_word' => array(1,1),
    )
  );

  if ('' !== $tempura) $order .= ' + '.$tempura;

  return $order;
}

function generate_poke()
{
  $params = array(
    'letters' => array(
	'はじめまして',
	'こんにちは',
	'こんばんは',
	'どうも',
	'たすけてくださーい！',
	'だれか',
	'たすけてー！',
	'おたすけください！',
	'よろしく',
	'きゅうじょ',
	'もとむ！',
	'たのむ！',
	'キンキューじたい',
	'はっせい！',
	'たよりにしてます！',
	'ううぅ。',
	'はぁ。',
	'やっちゃった…',
	'トホホ。',
	'やられた…。',
	'ミスしました。',
	'おしいんです！',
	'くやしい！！',
	'ＰＬＥＡＳＥ！',
	'ＨＥＹ！',
	'ＹＥＡＨ！',
	'ＨＥＬＰ！',
	'ＧＯ！',
	'ＧＯＯＤ！',
	'イヤーン！',
	'いっしょうの',
	'おねがいです',
	'おねがい！',
	'こんなのアリ？！',
	'ショック！',
	'もうちょいで',
	'ということで',
	'それでは',
	'つよい',
	'よわい',
	'わたしとしたことが',
	'ムチャをしました',
	'クリアもくぜん',
	'ユダンしました',
	'ちからつきました',
	'できればすぐ！',
	'これるものなら！',
	'カンタンです',
	'むずかしいです',
	'おれいに',
	'どうぞ',
	'いどうちゅう',
	'たんけんちゅう',
	'すみません',
	'おまたせしました',
	'ジャジャーン！',
	'とうじょう！',
	'ときどき',
	'また',
	'いつのひか',
	'やはり',
	'いつも',
	'いつまでも',
	'メリークリスマス',
	'プレゼント',
	'さいきょうの',
	'さいじゃくの',
	'キズだらけの',
	'さすらいの',
	'おひまな',
	'おちゃめな',
	'あぶない',
	'まじめな',
	'ふしぎな',
	'あやしい',
	'いかした',
	'たよれる',
	'かみさま',
	'みならい',
	'しょしんしゃ',
	'ハイレベル',
	'プロレベル',
	'とも',
	'どうし',
	'かぜがふきますよう',
	'よろしくてよ！',
	'ホントに',
	'ます',
	'エヘヘ',
	'やった！',
	'よかった',
	'いやあ',
	'オホホ',
	'めでたしめでたし',
	'おてをはいしゃく！',
	'パパンパンパン！',
	'かんりょう！',
	'パチパチパチ！',
	'まかせなさい！',
	'らくしょー！',
	'……てごわかった',
	'……やばかった',
	'せいこうしました',
	'なんとか',
	'おめでとー。',
	'がんばってね',
	'ガンバレ！',
	'おだいじに',
	'たっしゃで！',
	'こんごとも',
	'ヨロシク',
	'あとはよろしく！',
	'いつでも',
	'つづきをどうぞ',
	'いのちを',
	'たいせつに',
	'フッ……（さる）',
	'では！！',
	'またね♪',
	'ありがとう！',
	'さようなら！',
	'たすかりました！',
	'サンキューです！',
	'カンシャです！',
	'カンゲキです！',
	'なにかください',
	'しょくりょう',
	'どうぐ',
	'きぼう',
	'いりません',
	'ふよう',
	'だいじな',
	'きちょうな',
	'ごほうび',
	'きをつけて',
	'ちゅうい',
	'あります',
	'ありません',
	'たくさん',
	'すこし',
	'はやい',
	'おそい',
	'すごい',
	'とても',
	'ほしい',
	'います',
	'いません',
	'おれい',
	'よいおとしを',
	'あけまして',
	'おめでとう',
	'ことしもよろしく',
	'これで',
	'ひとまず',
	'ヒジョーに',
	'こころぐるしいですが',
	'ダンジョン',
	'１０かい',
	'２０かい',
	'３０かい',
	'４０かい',
	'５０かい',
	'６０かい',
	'７０かい',
	'８０かい',
	'９０かい',
	'９９かい',
	'むちゃをしました',
	'しか',
	'ポケモン',
	'たんけんたい',
	'とにかく',
	'こうかん',
	'なんでもよいので',
	'きゃー！',
	'おれいなんて',
	'いらないさ',
	'はやいものがち！',
	'ほしければ',
	'もうしわけございません',
	'さんじょう！',
	'はいけい',
	'けいぐ',
	'ぜんりゃく',
	'そうそう',
	'じゃあね～',
	'おはよう',
	'さらば！',
	'さあ！',
	'つどえ！',
	'あつまれ！',
	'ゆうきあるもの！',
	'きてください',
	'すばらしい',
	'あなたの',
	'きみの',
	'ちから',
	'ひつよう',
	'むぼうでした',
	'たのむから',
	'たいしたものは',
	'あげれませんが',
	'わたせませんが',
	'ものすごい',
	'またいつか',
	'あいましょう',
	'ごめんなさい',
	'ボンジュール！',
	'ヘイヘイ！',
	'ＨＥＬＬＯ！',
	'ＴＨＡＮＫ ＹＯＵ！',
	'ＧＯＯＤ ＢＹＥ！',
	'ＳＥＥ ＹＯＵ！',
      ),
    'endings' => array(
	'おねがいします',
	'（ためいき）',
	'（ドキドキ）',
	'ください',
	'あげます',
	'にいます',
	'です',
	'のみなさーん！',
	'にきゅうじょのてを！',
	'にあいのてを！',
	'いじょう',
	'いか',
	'まで',
	'のもの',
	'でゲス',
	'でした',
	'。',
	'！',
	'？',
	'…',
	'ー',
	'～',
	'♪',
	'Ｃｈｕ',
	'ｏｒｚ',
	'（＋Ｗ＋）',
	'（－ｏ－）',
	'（＋ｏ＋）',
	'（。。）',
	'（－。－）',
      ),
    'word_separators' => array('　'),
    'words_in_a_sentence' => array(1,4),
    'letters_in_a_word' => array(1,3),
  );
  return generate_random_sentence($params, false);
}

