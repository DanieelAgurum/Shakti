<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contacto - Shakti</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/contacto.css">
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
    ?>
</head>


<div class="container mt-3 mb-3  animate__animated animate__delay-0.5s animate__fadeInDown">
    <div class="form m-auto">
        <div class="contact-info">
            <h3 class="title">Comunicate con nosotros</h3>
            <p class="text">Si tienes alguna duda, comentario o sugerencia, no dudes en contactarnos.</p>
            <div class="info">
                <div class="social-information"> <i class="fa-solid fa-location-dot fa-bounce"></i>
                    <p>Nezahualcoyotl, Estado de México</p>
                </div>
                <div class="social-information"> <i class="fa-solid fa-envelope fa-bounce"></i>
                    <p>shakti@gmail.com</p>
                </div>
                <div class="social-information"> <i class="fa-solid fa-mobile fa-bounce"></i>
                    <p>+52 5678012353 </p>
                </div>
            </div>
            <div class="social-media">
                <p>Conecta con nosotros :</p>
                <div class="social-icons">
                    <a href="#"> <i class="bi bi-facebook"></i> </a>
                    <a href="#"> <i class="bi bi-instagram"></i> </a>
                    <a href="#"> <i class="bi bi-tiktok"></i> </a>
                </div>
            </div>
        </div>
        <div class="contact-info-form"> <span class="circle one"></span> <span class="circle two"></span>
            <form action="#" method="post" class="colortext" autocomplete="off">
                <div class="social-input-containers">
                    <input type="email" name="correo" id="inputblanco" class="input text-black" placeholder="Correo electrónico" value="<?php echo isset($_SESSION['correo']) ? strtolower($_SESSION['correo']) : " "; ?>">
                </div>
                <div class="social-input-containers textarea">
                    <textarea name="comentario" id="input" class="input" placeholder="Dejanos tu mensaje"></textarea>
                </div>
                <div class="float-end">
                    <input type="hidden" name="opcion" value="1">
                    <input type="submit" value="Enviar" class="btn btn-outline-dark">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../components/usuaria/footer.php'; ?>
<script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
</body>

</html>