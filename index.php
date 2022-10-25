<?php
    function parser($URL,$category,$db)
    {
        $file = file_get_contents("$URL");
        $position = 0;
        while (1)
        {
            $positionStart = strpos($file, '<li>', $position) + 4;
            $positionBreak = strpos($file,'</section>',$position);
            if($positionBreak<$positionStart)
                break;
            $positionEnd = strpos($file, '[', $positionStart) - 4;
            $length = $positionEnd - $positionStart;
            $engWord = substr($file, $positionStart, $length);
            $positionStart = strpos($file, ']', $positionEnd) + 6;
            $positionEnd1 = strpos($file, ',', $positionStart);
            $positionEnd2 = strpos($file, '</li>', $positionStart);
            if ($positionEnd1 < $positionEnd2)
                $positionEnd = $positionEnd1;
            else
                $positionEnd = $positionEnd2;
            $length = $positionEnd - $positionStart;
            $ruWord = substr($file, $positionStart, $length);
            dbFiller($engWord,$ruWord,$category,$db);
            $position = $positionEnd;
        }
    }

    function dbFiller($engWord, $ruWord, $category,$db)
    {
        $db->query("INSERT INTO words (eng_word,ru_word,category) VALUES (\"$engWord\",\"$ruWord\",\"$category\") ");
    }

    try
    {
        $db = new PDO('mysql:host=127.0.0.1;dbname=eng-ru', 'root', '');
    }
    catch (PDOException $e)
    {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    parser('https://dzodzo.ru/englishsub/slova-urovnya-a1-po-anglijskomu/','A1',$db);
    parser('https://dzodzo.ru/englishsub/slova-urovnya-a2-po-anglijskomu/','A2',$db);
    parser('https://dzodzo.ru/englishsub/slova-urovnya-b1-po-anglijskomu/','B1',$db);
    parser('https://dzodzo.ru/englishsub/slova-urovnya-b2-po-anglijskomu/','B2',$db);
    parser('https://dzodzo.ru/englishsub/slova-urovnya-c1-po-anglijskomu/','C1',$db);
//    parser('https://dzodzo.ru/englishsub/slova-urovnya-c2-po-anglijskomu/','C2',$db);