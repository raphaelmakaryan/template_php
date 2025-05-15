<?php
/*
&amp;&gt;&lt;&quot;&#x27;
$doc = new DOMDocument();
$newDiv = $doc->createElement('p', 'test');
$doc->getElementById('forName')->appendChild($newDiv);
echo $doc->saveHTML();
*/


session_start();
function haveValue($valeur)
{
    if ($valeur == "") {
        // echo "<div>ERRREURRRR</div>";
    }
    return $valeur;
}
function createFile($data)
{
    $file = 'fichier.txt';
    $current = file_get_contents($file);
    $current = $data;
    echo "suis dedans";
    file_put_contents($file, $current);
}

if ($_POST) {
    $name = haveValue(htmlspecialchars($_POST['forName']));
    echo $name;
    //$prenom = htmlspecialchars($_POST['forPrenom']);
    //$select = htmlspecialchars($_POST['leSelect']);
    //$email = htmlspecialchars($_POST['inputEmail']);
    //$radio = htmlspecialchars($_POST['radioOptions']);
    //$message = htmlspecialchars($_POST['inputMessage']);
    createFile($_POST);
    echo print_r($_POST);
}
?>

<?php include('./public/structure/header.php'); ?>

<main>
    <section class="mt-5 mb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <p class="fs-1 text-center">Voici ma page contact.php</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form method="post" action="contact.php" id="formContact" class="d-flex flex-column align-items-center needs-validation" novalidate>
                        <div class="d-flex mb-2 flex-column align-items-center">
                            <p class="fs-6 mb-1">Select</p>
                            <select class="form-select" aria-label="Default select example" id="leSelect" name="leSelect">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-2">
                            <label for="forName">Nom</label>
                            <input type="text" name="forName" id="forName" min="3" placeholder="">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-2">
                            <label for="forPrenom">Prénom</label>
                            <input type="text" name="forPrenom" id="forPrenom" min="3" placeholder="">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-4 mb-4">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="inputEmail" id="inputEmail" minlength="5">
                                <label for="inputEmail">Email</label>
                            </div>
                        </div>
                        <div class="form-floating d-flex flex-column align-items-center mt-2 mb-2">
                            <p class="fs-6 mb-1">Choix de contact</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radioOptions" id="radioOption1">
                                <label class="form-check-label" for="radioOption1">
                                    Tel
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radioOptions" id="radioOption2" checked>
                                <label class="form-check-label" for="radioOption2">
                                    Email
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radioOptions" id="radioOption3" checked>
                                <label class="form-check-label" for="radioOption3">
                                    RDV
                                </label>
                            </div>
                        </div>
                        <div class="form-floating d-flex flex-column align-items-center mt-3 mb-3">
                            <textarea class="form-control" placeholder="Leave a comment here" name="inputMessage" id="inputMessage" minlength="5"></textarea>
                            <label for="inputMessage">Message</label>
                        </div>
                        <div class=" d-flex flex-column align-items-center mt-2 mb-5">
                            <button type="submit" class="btn btn-primary">Envoyez</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <script>
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</main>

<?php include('./public/structure/footer.php'); ?>