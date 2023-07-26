<!DOCTYPE html>
<html>
<head>
    <title>Employment Center</title>
    <style>
        .description-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 999;
        }

        .vacancy-block {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .slider-container {
            width: 100%;
            height: 300px;
            overflow: hidden;
            position: relative;
        }

        .slider-images {
            width: 300%;
            display: flex;
            animation: slideAnimation 10s linear infinite;
        }

        .slider-image {
            width: 33.333%;
            height: 300px;
            object-fit: cover;
        }

        @keyframes slideAnimation {
            0% {
                transform: translate3d(0, 0, 0);
            }
            100% {
                transform: translate3d(-66.666%, 0, 0);
            }
        }
    </style>
</head>
<body>
    <div class="slider-container">
        <div class="slider-images">
            <img class="slider-image" src="image1.jpg" alt="Image 1">
            <img class="slider-image" src="image2.jpg" alt="Image 2">
            <img class="slider-image" src="image3.jpg" alt="Image 3">
            <img class="slider-image" src="image4.jpg" alt="Image 4">
        </div>
    </div>
    <div class="info-content">
        <?php

        $employmentCenterInfo = "Государственное казенное учреждение города Москвы, 
        осуществляющее реализацию государственной политики по обеспечению государственных гарантий 
        в области труда, занятости населения и трудовой миграции на территории города Москвы. 
        Центр занятости «Моя работа» - подведомственная организация Департамента труда 
        и социальной защиты населения города Москвы.";
        echo $employmentCenterInfo;
        ?>
    </div>

    <form method="get" action="">
    <label for="job-title">Вакансия:</label>
    <input type="text" name="job-title" id="job-title" value="<?php echo isset($_GET['job-title']) ? $_GET['job-title'] : ''; ?>">

    <label for="salary">Заработная плата:</label>
    <input type="text" name="salary" id="salary" value="<?php echo isset($_GET['salary']) ? $_GET['salary'] : ''; ?>">

    <button type="submit">Применить фильтр</button>
</form>

    <div class="vacancy-list">
        <h2>Список доступных вакансий:</h2>
        <ul>
            <?php

            $xml = simplexml_load_file('vacancies.xml');
            if ($xml) {
                $vacancies = $xml->vacancies->vacancy;
                $totalVacancies = count($vacancies);
                $vacanciesPerPage = 5;
                $totalPages = ceil($totalVacancies / $vacanciesPerPage);

                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $currentPage = max(1, min($_GET['page'], $totalPages));
                } else {
                    $currentPage = 1;
                }

                $startIndex = ($currentPage - 1) * $vacanciesPerPage;
                $endIndex = min($startIndex + $vacanciesPerPage, $totalVacancies);

                for ($i = $startIndex; $i < $endIndex; $i++) {
                    $vacancy = $vacancies[$i];
                    $position = $vacancy->job;
                    $company = $vacancy->company->name;
                    $location = $vacancy->address->location;
                    $salary = $vacancy->salary;

                    echo "<li>";
                    echo "<h3>$position</h3>";
                    echo "<p><strong>Название конпании:</strong> $company</p>";
                    echo "<p><strong>Местонахождение:</strong> $location</p>";
                    echo "<p><strong>Заработная плата:</strong> $salary</p>";
                    echo "<button onclick='showDescriptionPopup(\"description-popup-$i\")'>Подробнее</button>";
                    echo "</li>";
                }
                echo "<div class='pagination'>";
                for ($page = 1; $page <= $totalPages; $page++) {
                    echo "<a href='?page=$page'>$page</a>";
                }
                echo "</div>";
            } else {
                echo "No vacancies available at the moment.";
            }
            ?>
        </ul>
    </div>

    <?php

    for ($i = $startIndex; $i < $endIndex; $i++) {
        $vacancy = $vacancies[$i];
        $position = $vacancy->job;
        $description = $vacancy->description;
        $currency =$vacancy->currency;
        $schedule =$vacancy->schedule;
        $education =$vacancy->requirement->education;
        $experience =$vacancy->requirement->experience;
        $region =$vacancy->region;
        $companyName =$vacancy->company->name;
        $companyDescription =$vacancy->company->description;
        $companyINN =$vacancy->company->inn;
        $address =$vacancy->address->location;
        $coordinatesX =$vacancy->address->coordinates->x;
        $coordinatesY =$vacancy->address->coordinates->y;
        $creationDate =$vacancy->{'creation-date'};
        $publishDate =$vacancy->{'publish-date'};
        $realPublishDate =$vacancy->{'real-publish-date'};
        $updateDate =$vacancy->{'update-date'};
        $expires =$vacancy->{'expires'};

        echo "<div id='description-popup-$i' class='description-popup'>";
        echo "<h2>$position</h2>";
        echo "<p>$description</p>";
        echo "<h2>Заработная плата</h2>";
        echo "<p>$vacancy->salary $currency</p>";
        echo "<h2>График работы</h2>";
        echo "<p>$schedule</p>";
        echo "<h2>Образование</h2>";
        echo "<p>$education</p>";
        echo "<h2>Опыт работы</h2>";
        echo "<p>$experience</p>";
        echo "<h2>Местонахождение</h2>";
        echo "<p>$region</p>";
        echo "<h2>Название компании</h2>";
        echo "<p>$companyName</p>";
        echo "<h2>Расположение компании</h2>";
        echo "<p>$companyDescription</p>";
        echo "<h2>Инн компании</h2>";
        echo "<p>$companyINN</p>";
        echo "<h2>Адресс</h2>";
        echo "<p>$address</p>";
        echo "<h2>Координаты Х</h2>";
        echo "<p>$coordinatesX</p>";
        echo "<h2>Координаты Y</h2>";
        echo "<p>$coordinatesY</p>";
        echo "<h2>Дата создания объявления</h2>";
        echo "<p>$creationDate</p>";
        echo "<h2>Дата публикации объявления</h2>";
        echo "<p>$publishDate</p>";
        echo "<h2>Дата обновления объявления</h2>";
        echo "<p>$updateDate</p>";
        echo "<h2>Дата закрытия объявления</h2>";
        echo "<p>$expires</p>";

        echo "<button onclick='showApplyForm($i)'>Откликнуться на вакансию</button>";
        echo "<button onclick='closePopup(\"description-popup-$i\")'>Закрыть</button>";
        echo "</div>";
    }
    ?>


    <?php
    for ($i = $startIndex; $i < $endIndex; $i++) {
        echo "<div id='apply-form-$i' class='description-popup' style='display:none;'>";
        echo "<h2>Контактная</h2>";
        echo "<form id='contact-form-$i' action='submit_form.php' method='post'>";
        echo "<input type='text' name='last_name' placeholder='Фамилия' required><br>";
        echo "<input type='text' name='first_name' placeholder='Имя' required><br>";
        echo "<input type='text' name='middle_name' placeholder='Отчество'><br>";
        echo "<input type='email' name='email' placeholder='Ваша почта' required><br>";
        echo "<input type='tel' name='phone' placeholder='Номер вашего телефона (не обязательно)'><br>";
        echo "<button type='submit' disabled>Подтвердить</button>";
        echo "<button type='button' onclick='closePopup(\"apply-form-$i\")'>Закрыть</button>";
        echo "</form>";
        echo "</div>";
    }
    ?>

    <script>

function showDescriptionPopup(popupId) {
            document.getElementById(popupId).style.display = 'block';
        }

        function showApplyForm(applyFormId) {
            document.getElementById(applyFormId).style.display = 'block';
        }

        function closePopup(popupId) {
            document.getElementById(popupId).style.display = 'none';
        }

        function enableSubmitButton(formIndex) {
            const form = document.getElementById('contact-form-' + formIndex);
            const submitButton = form.querySelector('button[type="submit"]');
            const nameInput = form.querySelector('input[name="name"]');
            const emailInput = form.querySelector('input[name="email"]');

            form.addEventListener('input', function () {
                submitButton.disabled = !nameInput.value || !emailInput.value;
            });
        }

        for (let i = <?php echo $startIndex; ?>; i < <?php echo $endIndex; ?>; i++) {
            enableSubmitButton(i);
        }

for (let i = <?php echo $startIndex; ?>; i < <?php echo $endIndex; ?>; i++) {
    enableSubmitButton(i); }

    function showDescriptionPopup(popupId) {
        document.getElementById(popupId).style.display = 'block';
    }

    function showApplyForm(applyFormId) {
        document.getElementById(applyFormId).style.display = 'block';
    }

    function closePopup(popupId) {
        document.getElementById(popupId).style.display = 'none';

        const applyFormId = popupId.replace('description-popup', 'apply-form');
        if (document.getElementById(applyFormId)) {
            document.getElementById(applyFormId).style.display = 'none';
        }
    }

    function enableSubmitButton(formIndex) {
        const form = document.getElementById('contact-form-' + formIndex);
        const submitButton = form.querySelector('button[type="submit"]');
        const nameInput = form.querySelector('input[name="name"]');
        const emailInput = form.querySelector('input[name="email"]');

        form.addEventListener('input', function () {
            submitButton.disabled = !nameInput.value || !emailInput.value;
        });
    }

    for (let i = <?php echo $startIndex; ?>; i < <?php echo $endIndex; ?>; i++) {
        enableSubmitButton(i);
    }
    </script>
</body>
</html>
