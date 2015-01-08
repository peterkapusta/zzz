<?php
$db = new PDO('mysql:host=mysql51.websupport.sk;dbname=kamnabic;port=3309', 'tlhl3ze3', 'jq78Nh234Pm');
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Admin</title>

        <link href="/zzz/css/bootstrap.min.css" rel="stylesheet">
        <link href="style2.css" rel="stylesheet">
        <script src="/zzz/js/jquery.js"></script>
        <script src="/zzz/js/bootstrap.min.js"></script>

    </head>
    <body>
        <script>
            var url = window.location.href;
            if (url.indexOf(".php") > -1) {
                window.location = 'http://localhost/zzz/volam_sa_mato_a_som_super/';
            }
            
        </script>
        <div class="row">
            <h1>Admin</h1> 
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <form id='sampleform' method='post' action='http://localhost/zzz/volam_sa_mato_a_som_super/index.php' >
                        <div>
                            <p>Name:</p> <input type='text' name='name' />
                        </div>
                        <div>
                            <p>Description:</p> <textarea name='description'/></textarea>
                        </div>
                        <div>
                            <p>Map:</p> <textarea name='map' ></textarea>
                        </div>
                        <div>
                            <p>Length:</p> <input type='text' name='length' />  [m]
                        </div>
                        <div> County
                            <select name="county">
                                <option value="banskobystricky">Banska Bystrica</option>
                                <option value="bratislavsky">Bratislava</option>
                                <option value="kosicky">Kosice</option>
                                <option value="nitriansky">Nitra</option>
                                <option value="presovsky">Presov</option>
                                <option value="trenciansky">Trencin</option>
                                <option value="trnavsky">Trnava</option>
                                <option value="zilinsky">Zilina</option>
                            </select>
                        </div>
                        <div> Level of difficulty
                            <select name="difficulty">
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                        <input type="hidden" name="was_send" value="yes">
                        <div>
                            <input type='submit' class="btn btn-success" name='Submit' value='Submit' />
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <h3>List of locations</h3>
                    <table class="table">
                        <th>Add images</th>
                        <th>Location name</th>
                        <th>Count of images</th>
                        <th>Delete whole location</th>
                        <?php
                        if (isset($_POST['was_send'])) {
                            if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['county']) && isset($_POST['length']) && isset($_POST['difficulty']) && isset($_POST['map'])) {
                                try {

                                    $isSuggested = 'no';
                                    if (isset($_POST['is_suggested'])) {
                                        $isSuggested = 'yes';
                                    }

                                    $name = $_POST['name'];
                                    $alias = getAlias($name);
                                    $description = $_POST['description'];
                                    $map = $_POST['map'];
                                    $difficulty = $_POST['difficulty'];
                                    $length = $_POST['length'];


                                    $st = $db->prepare("INSERT INTO location (name, alias, description, map, is_suggested, difficulty, length) "
                                            . "VALUES(?, ?, ?, ?, ?, ?, ?)");


                                    $params = array($name, $alias, $description, $map, $isSuggested, $difficulty, $length);

                                    $st->execute($params);
                                    $locationId = $db->lastInsertId();

                                    $st = $db->prepare("SELECT id FROM county WHERE name LIKE ?");
                                    $st->execute(array($_POST['county']));
                                    $row = $st->fetch(PDO::FETCH_ASSOC);
                                    $countyId = $row['id'];

                                    $st = $db->prepare("INSERT INTO county_location (county_id, location_id) VALUES(?,?)");
                                    $params = array($countyId, $locationId);

                                    $st->execute($params);
                                } catch (PDOException $e) {
                                    echo 'Connection failed: ' . $e->getMessage();
                                }
                            } else {
                                echo 'Ale, ale ... Takto lahko to veru nepojde, moj, NEVYPLNIL SI VSETKO!!!!';
                            }
                        }


                        $sql = "SELECT id, name, alias, images FROM location ORDER BY id DESC";

                        $stmt = $db->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();

                        foreach ($result as $row) {
                            $images = $row['images'];
                            echo "<tr>"
                            . "<td><a href='/zzz/volam_sa_mato_a_som_super/upload.php?location={$row['alias']}' class='btn btn-primary add'>+</a></td>"
                            . "<td><p class='name'>{$row['name']}</p></td>"
                            . "<td><p class='images'>{$images}</p></td>"
                            . "<td><input type='button' class='btn btn-danger delete' value='X' id='{$row['id']}'></td>"
                            . "</tr>";
                        }
                        ?>
                    </table>
                </div>

            </div>
        </div>

        <script>
            $(document).ready(function () {

                $(".delete").click(function (event) {
                    if (confirm("Are you sure?")) {
                        var id = event.target.id;
                        $.ajax({
                            type: "POST",
                            url: "delete.php",
                            data: {location: id},
                            success: function (data) {
                                alert(data);
                                location.reload();
                            }
                        });
                    }
                    return false;

                });
            })

        </script>
    </body>
</html>

<?php

//mysql 5.1 server: mysql51.websupport.sk port: 3309
//$conn = new PDO('mysql:hostname=192.168.1.4;dbname=DB_TEST;port=3306','username','password');
//$dsn = "mysql:unix_socket=/tmp/mysql50.sock;dbname=$databaza";

function getAlias($string) {
    $string = iconv(mb_detect_encoding($string, mb_detect_order(), true), "UTF-8", $string);
    $str = removeAccents($string);
    $str = str_replace(" ", "-", $str);
    $str = strtolower($str);
    return $str;
}

function removeAccents($text) {
    $trans = array(
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Ç' => 'C', 'È' => 'E',
        'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N',
        'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
        'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
        'å' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i',
        'î' => 'i', 'ï' => 'i', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ÿ' => 'y', 'Ā' => 'A',
        'ā' => 'a', 'Ă' => 'A', 'ă' => 'a', 'Ą' => 'A', 'ą' => 'a', 'Ć' => 'C', 'ć' => 'c', 'Ĉ' => 'C',
        'ĉ' => 'c', 'Ċ' => 'C', 'ċ' => 'c', 'Č' => 'C', 'č' => 'c', 'Ď' => 'D', 'ď' => 'd', 'Đ' => 'D',
        'đ' => 'd', 'Ē' => 'E', 'ē' => 'e', 'Ĕ' => 'E', 'ĕ' => 'e', 'Ė' => 'E', 'ė' => 'e', 'Ę' => 'E',
        'ę' => 'e', 'Ě' => 'E', 'ě' => 'e', 'Ĝ' => 'G', 'ĝ' => 'g', 'Ğ' => 'G', 'ğ' => 'g', 'Ġ' => 'G',
        'ġ' => 'g', 'Ģ' => 'G', 'ģ' => 'g', 'Ĥ' => 'H', 'ĥ' => 'h', 'Ħ' => 'H', 'ħ' => 'h', 'Ĩ' => 'I',
        'ĩ' => 'i', 'Ī' => 'I', 'ī' => 'i', 'Ĭ' => 'I', 'ĭ' => 'i', 'Į' => 'I', 'į' => 'i', 'İ' => 'I',
        'ı' => 'i', 'Ĵ' => 'J', 'ĵ' => 'j', 'Ķ' => 'K', 'ķ' => 'k', 'Ĺ' => 'L', 'ĺ' => 'l', 'Ļ' => 'L',
        'ļ' => 'l', 'Ľ' => 'L', 'ľ' => 'l', 'Ŀ' => 'L', 'ŀ' => 'l', 'Ł' => 'L', 'ł' => 'l', 'Ń' => 'N',
        'ń' => 'n', 'Ņ' => 'N', 'ņ' => 'n', 'Ň' => 'N', 'ň' => 'n', 'ŉ' => 'n', 'Ō' => 'O', 'ō' => 'o',
        'Ŏ' => 'O', 'ŏ' => 'o', 'Ő' => 'O', 'ő' => 'o', 'Ŕ' => 'R', 'ŕ' => 'r', 'Ŗ' => 'R', 'ŗ' => 'r',
        'Ř' => 'R', 'ř' => 'r', 'Ś' => 'S', 'ś' => 's', 'Ŝ' => 'S', 'ŝ' => 's', 'Ş' => 'S', 'ş' => 's',
        'Š' => 'S', 'š' => 's', 'Ţ' => 'T', 'ţ' => 't', 'Ť' => 'T', 'ť' => 't', 'Ŧ' => 'T', 'ŧ' => 't',
        'Ũ' => 'U', 'ũ' => 'u', 'Ū' => 'U', 'ū' => 'u', 'Ŭ' => 'U', 'ŭ' => 'u', 'Ů' => 'U', 'ů' => 'u',
        'Ű' => 'U', 'ű' => 'u', 'Ų' => 'U', 'ų' => 'u', 'Ŵ' => 'W', 'ŵ' => 'w', 'Ŷ' => 'Y', 'ŷ' => 'y',
        'Ÿ' => 'Y', 'Ź' => 'Z', 'ź' => 'z', 'Ż' => 'Z', 'ż' => 'z', 'Ž' => 'Z', 'ž' => 'z', 'ƀ' => 'b',
        'Ɓ' => 'B', 'Ƃ' => 'B', 'ƃ' => 'b', 'Ƈ' => 'C', 'ƈ' => 'c', 'Ɗ' => 'D', 'Ƌ' => 'D', 'ƌ' => 'd',
        'Ƒ' => 'F', 'ƒ' => 'f', 'Ɠ' => 'G', 'Ɨ' => 'I', 'Ƙ' => 'K', 'ƙ' => 'k', 'ƚ' => 'l', 'Ɲ' => 'N',
        'ƞ' => 'n', 'Ɵ' => 'O', 'Ơ' => 'O', 'ơ' => 'o', 'Ƥ' => 'P', 'ƥ' => 'p', 'ƫ' => 't', 'Ƭ' => 'T',
        'ƭ' => 't', 'Ʈ' => 'T', 'Ư' => 'U', 'ư' => 'u', 'Ʋ' => 'V', 'Ƴ' => 'Y', 'ƴ' => 'y', 'Ƶ' => 'Z',
        'ƶ' => 'z', 'ǅ' => 'D', 'ǈ' => 'L', 'ǋ' => 'N', 'Ǎ' => 'A', 'ǎ' => 'a', 'Ǐ' => 'I', 'ǐ' => 'i',
        'Ǒ' => 'O', 'ǒ' => 'o', 'Ǔ' => 'U', 'ǔ' => 'u', 'Ǖ' => 'U', 'ǖ' => 'u', 'Ǘ' => 'U', 'ǘ' => 'u',
        'Ǚ' => 'U', 'ǚ' => 'u', 'Ǜ' => 'U', 'ǜ' => 'u', 'Ǟ' => 'A', 'ǟ' => 'a', 'Ǡ' => 'A', 'ǡ' => 'a',
        'Ǥ' => 'G', 'ǥ' => 'g', 'Ǧ' => 'G', 'ǧ' => 'g', 'Ǩ' => 'K', 'ǩ' => 'k', 'Ǫ' => 'O', 'ǫ' => 'o',
        'Ǭ' => 'O', 'ǭ' => 'o', 'ǰ' => 'j', 'ǲ' => 'D', 'Ǵ' => 'G', 'ǵ' => 'g', 'Ǹ' => 'N', 'ǹ' => 'n',
        'Ǻ' => 'A', 'ǻ' => 'a', 'Ǿ' => 'O', 'ǿ' => 'o', 'Ȁ' => 'A', 'ȁ' => 'a', 'Ȃ' => 'A', 'ȃ' => 'a',
        'Ȅ' => 'E', 'ȅ' => 'e', 'Ȇ' => 'E', 'ȇ' => 'e', 'Ȉ' => 'I', 'ȉ' => 'i', 'Ȋ' => 'I', 'ȋ' => 'i',
        'Ȍ' => 'O', 'ȍ' => 'o', 'Ȏ' => 'O', 'ȏ' => 'o', 'Ȑ' => 'R', 'ȑ' => 'r', 'Ȓ' => 'R', 'ȓ' => 'r',
        'Ȕ' => 'U', 'ȕ' => 'u', 'Ȗ' => 'U', 'ȗ' => 'u', 'Ș' => 'S', 'ș' => 's', 'Ț' => 'T', 'ț' => 't',
        'Ȟ' => 'H', 'ȟ' => 'h', 'Ƞ' => 'N', 'ȡ' => 'd', 'Ȥ' => 'Z', 'ȥ' => 'z', 'Ȧ' => 'A', 'ȧ' => 'a',
        'Ȩ' => 'E', 'ȩ' => 'e', 'Ȫ' => 'O', 'ȫ' => 'o', 'Ȭ' => 'O', 'ȭ' => 'o', 'Ȯ' => 'O', 'ȯ' => 'o',
        'Ȱ' => 'O', 'ȱ' => 'o', 'Ȳ' => 'Y', 'ȳ' => 'y', 'ȴ' => 'l', 'ȵ' => 'n', 'ȶ' => 't', 'ȷ' => 'j',
        'Ⱥ' => 'A', 'Ȼ' => 'C', 'ȼ' => 'c', 'Ƚ' => 'L', 'Ⱦ' => 'T', 'ȿ' => 's', 'ɀ' => 'z', 'Ƀ' => 'B',
        'Ʉ' => 'U', 'Ɇ' => 'E', 'ɇ' => 'e', 'Ɉ' => 'J', 'ɉ' => 'j', 'ɋ' => 'q', 'Ɍ' => 'R', 'ɍ' => 'r',
        'Ɏ' => 'Y', 'ɏ' => 'y', 'ɓ' => 'b', 'ɕ' => 'c', 'ɖ' => 'd', 'ɗ' => 'd', 'ɟ' => 'j', 'ɠ' => 'g',
        'ɦ' => 'h', 'ɨ' => 'i', 'ɫ' => 'l', 'ɬ' => 'l', 'ɭ' => 'l', 'ɱ' => 'm', 'ɲ' => 'n', 'ɳ' => 'n',
        'ɵ' => 'o', 'ɼ' => 'r', 'ɽ' => 'r', 'ɾ' => 'r', 'ʂ' => 's', 'ʄ' => 'j', 'ʈ' => 't', 'ʉ' => 'u',
        'ʋ' => 'v', 'ʐ' => 'z', 'ʑ' => 'z', 'ʝ' => 'j', 'ʠ' => 'q', 'ͣ' => 'a', 'ͤ' => 'e', 'ͥ' => 'i',
        'ͦ' => 'o', 'ͧ' => 'u', 'ͨ' => 'c', 'ͩ' => 'd', 'ͪ' => 'h', 'ͫ' => 'm', 'ͬ' => 'r', 'ͭ' => 't',
        'ͮ' => 'v', 'ͯ' => 'x', 'ᵢ' => 'i', 'ᵣ' => 'r', 'ᵤ' => 'u', 'ᵥ' => 'v', 'ᵬ' => 'b', 'ᵭ' => 'd',
        'ᵮ' => 'f', 'ᵯ' => 'm', 'ᵰ' => 'n', 'ᵱ' => 'p', 'ᵲ' => 'r', 'ᵳ' => 'r', 'ᵴ' => 's', 'ᵵ' => 't',
        'ᵶ' => 'z', 'ᵻ' => 'i', 'ᵽ' => 'p', 'ᵾ' => 'u', 'ᶀ' => 'b', 'ᶁ' => 'd', 'ᶂ' => 'f', 'ᶃ' => 'g',
        'ᶄ' => 'k', 'ᶅ' => 'l', 'ᶆ' => 'm', 'ᶇ' => 'n', 'ᶈ' => 'p', 'ᶉ' => 'r', 'ᶊ' => 's', 'ᶌ' => 'v',
        'ᶍ' => 'x', 'ᶎ' => 'z', 'ᶏ' => 'a', 'ᶑ' => 'd', 'ᶒ' => 'e', 'ᶖ' => 'i', 'ᶙ' => 'u', '᷊' => 'r',
        'ᷗ' => 'c', 'ᷚ' => 'g', 'ᷜ' => 'k', 'ᷝ' => 'l', 'ᷠ' => 'n', 'ᷣ' => 'r', 'ᷤ' => 's', 'ᷦ' => 'z',
        'Ḁ' => 'A', 'ḁ' => 'a', 'Ḃ' => 'B', 'ḃ' => 'b', 'Ḅ' => 'B', 'ḅ' => 'b', 'Ḇ' => 'B', 'ḇ' => 'b',
        'Ḉ' => 'C', 'ḉ' => 'c', 'Ḋ' => 'D', 'ḋ' => 'd', 'Ḍ' => 'D', 'ḍ' => 'd', 'Ḏ' => 'D', 'ḏ' => 'd',
        'Ḑ' => 'D', 'ḑ' => 'd', 'Ḓ' => 'D', 'ḓ' => 'd', 'Ḕ' => 'E', 'ḕ' => 'e', 'Ḗ' => 'E', 'ḗ' => 'e',
        'Ḙ' => 'E', 'ḙ' => 'e', 'Ḛ' => 'E', 'ḛ' => 'e', 'Ḝ' => 'E', 'ḝ' => 'e', 'Ḟ' => 'F', 'ḟ' => 'f',
        'Ḡ' => 'G', 'ḡ' => 'g', 'Ḣ' => 'H', 'ḣ' => 'h', 'Ḥ' => 'H', 'ḥ' => 'h', 'Ḧ' => 'H', 'ḧ' => 'h',
        'Ḩ' => 'H', 'ḩ' => 'h', 'Ḫ' => 'H', 'ḫ' => 'h', 'Ḭ' => 'I', 'ḭ' => 'i', 'Ḯ' => 'I', 'ḯ' => 'i',
        'Ḱ' => 'K', 'ḱ' => 'k', 'Ḳ' => 'K', 'ḳ' => 'k', 'Ḵ' => 'K', 'ḵ' => 'k', 'Ḷ' => 'L', 'ḷ' => 'l',
        'Ḹ' => 'L', 'ḹ' => 'l', 'Ḻ' => 'L', 'ḻ' => 'l', 'Ḽ' => 'L', 'ḽ' => 'l', 'Ḿ' => 'M', 'ḿ' => 'm',
        'Ṁ' => 'M', 'ṁ' => 'm', 'Ṃ' => 'M', 'ṃ' => 'm', 'Ṅ' => 'N', 'ṅ' => 'n', 'Ṇ' => 'N', 'ṇ' => 'n',
        'Ṉ' => 'N', 'ṉ' => 'n', 'Ṋ' => 'N', 'ṋ' => 'n', 'Ṍ' => 'O', 'ṍ' => 'o', 'Ṏ' => 'O', 'ṏ' => 'o',
        'Ṑ' => 'O', 'ṑ' => 'o', 'Ṓ' => 'O', 'ṓ' => 'o', 'Ṕ' => 'P', 'ṕ' => 'p', 'Ṗ' => 'P', 'ṗ' => 'p',
        'Ṙ' => 'R', 'ṙ' => 'r', 'Ṛ' => 'R', 'ṛ' => 'r', 'Ṝ' => 'R', 'ṝ' => 'r', 'Ṟ' => 'R', 'ṟ' => 'r',
        'Ṡ' => 'S', 'ṡ' => 's', 'Ṣ' => 'S', 'ṣ' => 's', 'Ṥ' => 'S', 'ṥ' => 's', 'Ṧ' => 'S', 'ṧ' => 's',
        'Ṩ' => 'S', 'ṩ' => 's', 'Ṫ' => 'T', 'ṫ' => 't', 'Ṭ' => 'T', 'ṭ' => 't', 'Ṯ' => 'T', 'ṯ' => 't',
        'Ṱ' => 'T', 'ṱ' => 't', 'Ṳ' => 'U', 'ṳ' => 'u', 'Ṵ' => 'U', 'ṵ' => 'u', 'Ṷ' => 'U', 'ṷ' => 'u',
        'Ṹ' => 'U', 'ṹ' => 'u', 'Ṻ' => 'U', 'ṻ' => 'u', 'Ṽ' => 'V', 'ṽ' => 'v', 'Ṿ' => 'V', 'ṿ' => 'v',
        'Ẁ' => 'W', 'ẁ' => 'w', 'Ẃ' => 'W', 'ẃ' => 'w', 'Ẅ' => 'W', 'ẅ' => 'w', 'Ẇ' => 'W', 'ẇ' => 'w',
        'Ẉ' => 'W', 'ẉ' => 'w', 'Ẋ' => 'X', 'ẋ' => 'x', 'Ẍ' => 'X', 'ẍ' => 'x', 'Ẏ' => 'Y', 'ẏ' => 'y',
        'Ẑ' => 'Z', 'ẑ' => 'z', 'Ẓ' => 'Z', 'ẓ' => 'z', 'Ẕ' => 'Z', 'ẕ' => 'z', 'ẖ' => 'h', 'ẗ' => 't',
        'ẘ' => 'w', 'ẙ' => 'y', 'ẚ' => 'a', 'Ạ' => 'A', 'ạ' => 'a', 'Ả' => 'A', 'ả' => 'a', 'Ấ' => 'A',
        'ấ' => 'a', 'Ầ' => 'A', 'ầ' => 'a', 'Ẩ' => 'A', 'ẩ' => 'a', 'Ẫ' => 'A', 'ẫ' => 'a', 'Ậ' => 'A',
        'ậ' => 'a', 'Ắ' => 'A', 'ắ' => 'a', 'Ằ' => 'A', 'ằ' => 'a', 'Ẳ' => 'A', 'ẳ' => 'a', 'Ẵ' => 'A',
        'ẵ' => 'a', 'Ặ' => 'A', 'ặ' => 'a', 'Ẹ' => 'E', 'ẹ' => 'e', 'Ẻ' => 'E', 'ẻ' => 'e', 'Ẽ' => 'E',
        'ẽ' => 'e', 'Ế' => 'E', 'ế' => 'e', 'Ề' => 'E', 'ề' => 'e', 'Ể' => 'E', 'ể' => 'e', 'Ễ' => 'E',
        'ễ' => 'e', 'Ệ' => 'E', 'ệ' => 'e', 'Ỉ' => 'I', 'ỉ' => 'i', 'Ị' => 'I', 'ị' => 'i', 'Ọ' => 'O',
        'ọ' => 'o', 'Ỏ' => 'O', 'ỏ' => 'o', 'Ố' => 'O', 'ố' => 'o', 'Ồ' => 'O', 'ồ' => 'o', 'Ổ' => 'O',
        'ổ' => 'o', 'Ỗ' => 'O', 'ỗ' => 'o', 'Ộ' => 'O', 'ộ' => 'o', 'Ớ' => 'O', 'ớ' => 'o', 'Ờ' => 'O',
        'ờ' => 'o', 'Ở' => 'O', 'ở' => 'o', 'Ỡ' => 'O', 'ỡ' => 'o', 'Ợ' => 'O', 'ợ' => 'o', 'Ụ' => 'U',
        'ụ' => 'u', 'Ủ' => 'U', 'ủ' => 'u', 'Ứ' => 'U', 'ứ' => 'u', 'Ừ' => 'U', 'ừ' => 'u', 'Ử' => 'U',
        'ử' => 'u', 'Ữ' => 'U', 'ữ' => 'u', 'Ự' => 'U', 'ự' => 'u', 'Ỳ' => 'Y', 'ỳ' => 'y', 'Ỵ' => 'Y',
        'ỵ' => 'y', 'Ỷ' => 'Y', 'ỷ' => 'y', 'Ỹ' => 'Y', 'ỹ' => 'y', 'Ỿ' => 'Y', 'ỿ' => 'y', 'ⁱ' => 'i',
        'ⁿ' => 'n', 'ₐ' => 'a', 'ₑ' => 'e', 'ₒ' => 'o', 'ₓ' => 'x', '⒜' => 'a', '⒝' => 'b', '⒞' => 'c',
        '⒟' => 'd', '⒠' => 'e', '⒡' => 'f', '⒢' => 'g', '⒣' => 'h', '⒤' => 'i', '⒥' => 'j', '⒦' => 'k',
        '⒧' => 'l', '⒨' => 'm', '⒩' => 'n', '⒪' => 'o', '⒫' => 'p', '⒬' => 'q', '⒭' => 'r', '⒮' => 's',
        '⒯' => 't', '⒰' => 'u', '⒱' => 'v', '⒲' => 'w', '⒳' => 'x', '⒴' => 'y', '⒵' => 'z', 'Ⓐ' => 'A',
        'Ⓑ' => 'B', 'Ⓒ' => 'C', 'Ⓓ' => 'D', 'Ⓔ' => 'E', 'Ⓕ' => 'F', 'Ⓖ' => 'G', 'Ⓗ' => 'H', 'Ⓘ' => 'I',
        'Ⓙ' => 'J', 'Ⓚ' => 'K', 'Ⓛ' => 'L', 'Ⓜ' => 'M', 'Ⓝ' => 'N', 'Ⓞ' => 'O', 'Ⓟ' => 'P', 'Ⓠ' => 'Q',
        'Ⓡ' => 'R', 'Ⓢ' => 'S', 'Ⓣ' => 'T', 'Ⓤ' => 'U', 'Ⓥ' => 'V', 'Ⓦ' => 'W', 'Ⓧ' => 'X', 'Ⓨ' => 'Y',
        'Ⓩ' => 'Z', 'ⓐ' => 'a', 'ⓑ' => 'b', 'ⓒ' => 'c', 'ⓓ' => 'd', 'ⓔ' => 'e', 'ⓕ' => 'f', 'ⓖ' => 'g',
        'ⓗ' => 'h', 'ⓘ' => 'i', 'ⓙ' => 'j', 'ⓚ' => 'k', 'ⓛ' => 'l', 'ⓜ' => 'm', 'ⓝ' => 'n', 'ⓞ' => 'o',
        'ⓟ' => 'p', 'ⓠ' => 'q', 'ⓡ' => 'r', 'ⓢ' => 's', 'ⓣ' => 't', 'ⓤ' => 'u', 'ⓥ' => 'v', 'ⓦ' => 'w',
        'ⓧ' => 'x', 'ⓨ' => 'y', 'ⓩ' => 'z', 'Ⱡ' => 'L', 'ⱡ' => 'l', 'Ɫ' => 'L', 'Ᵽ' => 'P', 'Ɽ' => 'R',
        'ⱥ' => 'a', 'ⱦ' => 't', 'Ⱨ' => 'H', 'ⱨ' => 'h', 'Ⱪ' => 'K', 'ⱪ' => 'k', 'Ⱬ' => 'Z', 'ⱬ' => 'z',
        'Ɱ' => 'M', 'ⱱ' => 'v', 'Ⱳ' => 'W', 'ⱳ' => 'w', 'ⱴ' => 'v', 'ⱸ' => 'e', 'ⱺ' => 'o', 'ⱼ' => 'j',
        'Ꝁ' => 'K', 'ꝁ' => 'k', 'Ꝃ' => 'K', 'ꝃ' => 'k', 'Ꝅ' => 'K', 'ꝅ' => 'k', 'Ꝉ' => 'L', 'ꝉ' => 'l',
        'Ꝋ' => 'O', 'ꝋ' => 'o', 'Ꝍ' => 'O', 'ꝍ' => 'o', 'Ꝑ' => 'P', 'ꝑ' => 'p', 'Ꝓ' => 'P', 'ꝓ' => 'p',
        'Ꝕ' => 'P', 'ꝕ' => 'p', 'Ꝗ' => 'Q', 'ꝗ' => 'q', 'Ꝙ' => 'Q', 'ꝙ' => 'q', 'Ꝛ' => 'R', 'ꝛ' => 'r',
        'Ꝟ' => 'V', 'ꝟ' => 'v', 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E', 'Ｆ' => 'F',
        'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J', 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N',
        'Ｏ' => 'O', 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T', 'Ｕ' => 'U', 'Ｖ' => 'V',
        'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y', 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
        'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l',
        'ｍ' => 'm', 'ｎ' => 'n', 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't',
        'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x', 'ｙ' => 'y', 'ｚ' => 'z',);
    return strtr($text, $trans);
}
?>

