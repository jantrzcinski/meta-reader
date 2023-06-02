<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta Reader</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="<?php echo $assetPath ?>css/style.css" rel="stylesheet" />
    <link href="<?php echo $assetPath ?>css/google.css" rel="stylesheet" />
</head>

<body data-url="<?php echo $basePath ?>">

    <div class="container">
        <h1 class="mb-5">Meta reader</h1>

        <div id="alerts"></div>

        <form id="metaForm" method="POST">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <div class="mb-3 form-floating">
                        <input required type="url" class="form-control" id="urlField" placeholder="https://example.com">
                        <label for="urlField" class="form-label">URL</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-3">
                        <button id="metaAction" class="btn btn-primary btn-lg">Pobierz</button>
                    </div>
                </div>
            </div>

            <div id="spinner" class="d-none spinner-grow" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="titleField" class="form-label">Tytuł strony - meta title</label>
                        <div class="float-end"><span id="titleLength">0</span>/<span id="titleMaxLength"><?php echo $limitTitle['length'] ?></span> znaków | <span id="titleWidth">0</span>/<span id="titleMaxWidth"><?php echo $limitTitle['width'] ?></span> pikseli</div>
                        <textarea name="title" class="form-control" id="titleField" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="descriptionField" class="form-label">Opis strony - meta description</label>
                        <div class="float-end"><span id="descriptionLength">0</span>/<span id="descriptionMaxLength"><?php echo $limitDescription['length'] ?></span> znaków | <span id="descriptionWidth">0</span>/<span id="descriptionMaxWidth"><?php echo $limitDescription['width'] ?></span> pikseli</div>
                        <textarea name="description" class="form-control" id="descriptionField" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <button id="resetForm" class="btn btn-warning">Wyczyść</button>
        </form>
    </div>


    <div class="container  mt-5">
        <h2>Przykładowy wynik</h2>

        <div id="googleSerp">
            <div class="yuRUbf"><a href="https://www.speedtest.pl/" jscontroller="M9mgyc" jsname="qOiK6e" jsaction="rcuQ6b:npT2md" data-ved="2ahUKEwj6oZiM9qT_AhUVHHcKHXA-A1wQFnoECAYQAQ" ping="/url?sa=t&amp;source=web&amp;rct=j&amp;url=https://www.speedtest.pl/&amp;ved=2ahUKEwj6oZiM9qT_AhUVHHcKHXA-A1wQFnoECAYQAQ"><br>
                    <h3 id="googleTitle" class="LC20lb MBeuO DKV0Md">Speed Test - test prędkości łącza internetowego - SpeedTest.pl</h3>
                    <div class="TbwUpd NJjxre iUh30 apx8Vc ojE3Fb"><span class="H9lube">
                            <div class="eqA2re NjwKYd Vwoesf" aria-hidden="true"><img class="XNo5Ab" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACeUlEQVR4AYSTA5AlMRRFb5KeHds2Smvbtm3btm3btm3btm3+30k2Pfacqji5N5W8R5CMnJWn2f8zmevoOi/FuQjmghMhxBMp5CGN0bUPjw/6hkSQJIerTG+sm/kknXM3zjliiohphYAS+QKC3i/PDl+YQiBvjVmj1OH+xkYfD3uULxaFiGA3QAJ3H73Dpj3XcP/JewjBUSxf1OQ1Mxr3QByF68xvlK/GHJmn2iw5Z9UZada5TI7OhRw+ZYvMXriJ3HL8r9x20tQKClKm6VK7v//Mj5Wze+Nq2dCqTi6kxqVLl9C6dWtMmbMFn03eUJpfCZHBVAjUZoy5B3g7o1nNHEiNJUuWoFChQhg6dCgK5QqAnbWEelgn9T71qTpcUtMYyhQOh3plJMZkMqFdu3Zo3rw5WrVqhUqVKoEQwN+dRj+qKiU1JRBEQREa4IrE/P79GyVLlsSZM2eQLVs2jB8/HnE42BIIzqEIooa7haaBUoLE2NjYoHr16rCzs8PatWthaWmJOKSU0Hn0DWAIPNWUwIu3P5Ccnj174tq1a4iIiEBiPn/X42LkKVXuBy00hrNX3yhFieSEhIQgMcoc95//jhYQgh+klpbaenWDD5++/sXOI4+QEdce/MSHz/8M969KbTW7dmq1KUeRRm8po9UePf8OxtSD+juCEJLC+fLdbzh9/VtcCHduVdXrdPyu1oMPjqKE9mcaU6FshzxZvODrYQtGKT5+NeHOk5/4+pMbBqqwyZ3qBPRIkUwdRxxtTCmbpETcjHcxHtdCFS26MKN8UaV3twYhKZMpjl4TTttLkDrKpZQ6GKwxRtSh/7sPxHtZWZlXlMSpoWRnAAmZK89LlyKNAAAAAElFTkSuQmCC" style="height:18px;width:18px" alt="" data-atf="1" data-frt="0"></div>
                        </span>
                        <div><span class="VuuXrf host">speedtest.pl</span>
                            <div class="byrV5b"><cite class="apx8Vc tjvcx GvPZzd cHaqb url" role="text" style="max-width:315px">https://www.speedtest.pl</cite></div>
                        </div>
                    </div>
                </a>
                <div class="B6fmyf byrV5b Mg1HEd">
                    <div class="TbwUpd iUh30 apx8Vc ojE3Fb"><span class="H9lube">
                            <div class="eqA2re NjwKYd" style="height:18px;width:18px"></div>
                        </span>
                        <div><span class="VuuXrf">speedtest.pl</span>
                            <div class="byrV5b"><cite class="apx8Vc tjvcx GvPZzd cHaqb" role="text" style="max-width:315px">https://www.speedtest.pl</cite>
                                <div class="eFM0qc BCF2pd iUh30"></div>
                            </div>
                        </div>
                    </div>
                    <div class="csDOgf BCF2pd L48a4c">
                        <div jscontroller="exgaYe" data-bsextraheight="0" data-isdesktop="true" jsdata="l7Bhpb;_;B1jk/0 cECq7c;_;B1jk/4" data-ved="2ahUKEwj6oZiM9qT_AhUVHHcKHXA-A1wQ2esEegQIBhAH">
                            <div role="button" tabindex="0" jsaction="RvIhPd" jsname="I3kE2c" class="iTPLzd rNSxBe lUn2nc" style="position:absolute" aria-label="O&nbsp;tym wyniku"><span jsname="czHhOd" class="D6lY4c mBswFe"><span jsname="Bil8Ae" class="xTFaxe z1asCe SaPW2b" style="height:18px;line-height:18px;width:18px"><svg focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
                                        </svg></span></span></div><span jsname="zOVa8" data-ved="2ahUKEwj6oZiM9qT_AhUVHHcKHXA-A1wQh-4GegQIBhAI"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="IsZvec">
                <div class="VwiC3b yXK7lf MUxGbd yDYNvb lyLwlc lEBKkf" style="-webkit-line-clamp:2">
                    <span id="googleDescription">Speed <em>Test</em> ® - zmierz prędkość i jakość łącza internetowego używając najpopularniejszego i najbardziej wiarygodnego testu w Polsce. Sprawdź w rankingach&nbsp;...</span>
                </div>
            </div>
        </div>

    </div>

    <?php if (!empty($lastLogs)) : ?>

        <div class="container mt-5">
            <h2>Ostatnie sprawdzenia</h2>
            <table id="metaResults" class="table table-striped">
                <thead>
                    <th>
                        URL
                    </th>
                    <th>
                        Data
                    </th>
                </thead>
                <tbody>
                    <?php foreach ($lastLogs as $item) : ?>
                        <tr>
                            <td>
                                <a href="<?php echo $item[0]; ?>" target="_blank"><?php echo $item[0]; ?></a>
                            </td>
                            <td>
                                <?php echo $item[1]; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="<?php echo $assetPath ?>js/app.js"></script>
</body>

</html>