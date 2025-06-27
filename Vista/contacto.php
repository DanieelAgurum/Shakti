
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contacto - Shakti</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .form-control {
            background-color: transparent;
            border: transparent;
            border-radius: 0;
            border-bottom: 1px solid white;
        }

        .form-control::placeholder {
            color: white;
        }

        .form-control:focus {
            background-color: transparent;
            border: 0;
            border-radius: 0;
            border-bottom: 1px solid black;
        }

        .form-control:focus {
            background-color: transparent;
            box-shadow: none;
            border-color: white;
            outline: none;
        }

        .btn {
            border-radius: 20px;
            height: auto;
        }

        .letras {
            display: none;
        }

        .form {
            width: 100%;
            max-width: 820px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 0 20px 1px rgba(0, 0, 0, 0.486);
            z-index: 1000;
            overflow: hidden;
            display: grid;
            grid-template-columns: repeat(2, 1fr)
        }

        .contact-info-form {
            background-image: linear-gradient(to top, #fad0c4 0%, #ffd1ff 100%);
            position: relative
        }

        .circle {
            border-radius: 50%;
            background-image: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            position: absolute
        }

        .circle.one {
            width: 130px;
            height: 130px;
            top: 130px;
            right: -40px
        }

        .circle.two {
            width: 80px;
            height: 80px;
            top: 10px;
            right: 30px
        }

        .contact-info-form:before {
            content: "";
            position: absolute;
            width: 26px;
            height: 26px;
            background-image: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            transform: rotate(45deg);
            bottom: 66px;
            left: -13px
        }

        form {
            padding: 2.3rem 2.2rem;
            z-index: 10;
            overflow: hidden;
            position: relative
        }

        .title {
            color: #fff;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
            margin-bottom: 0.7rem
        }

        .social-input-containers {
            position: relative;
            margin: 1rem 0
        }

        .input {
            width: 100%;
            outline: none;
            border: 2px solid white;
            background: none;
            padding: 0.6rem 1.2rem;
            color: #fff;
            font-weight: 500;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            border-radius: 4px;
            transition: 0.3s
        }

        textarea.input {
            color: white;
            padding: 0.8rem 1.2rem;
            min-height: 150px;
            border-radius: 4px;
            resize: none;
            overflow-y: auto
        }

        textarea.input::placeholder {
            color: #000;
        }

        .social-input-containers label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            padding: 0 0.4rem;
            color: black;
            font-size: 0.9rem;
            font-weight: 400;
            pointer-events: none;
            z-index: 1000;
            transition: 0.5s
        }

        .social-input-containers input {
            color: #000;
        }

        .social-input-containers input::placeholder {
            color: #000;
        }

        .social-input-containers.textarea label {
            top: 1rem;
            transform: translateY(0)
        }

        .enviar {
            float: right;
            padding: 0.6rem 1.3rem;
            background-color: #fff;
            border: 2px solid #fafafa;
            font-size: 0.95rem;
            color: #1abc9c;
            line-height: 1;
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            transition: 0.3s;
            margin: 0
        }

        .enviar:hover {
            background-color: transparent;
            color: #fff
        }

        .social-input-containers span {
            position: absolute;
            top: 0;
            left: 25px;
            transform: translateY(-50%);
            font-size: 0.8rem;
            padding: 0 0.4rem;
            color: transparent;
            pointer-events: none;
            z-index: 500
        }

        .social-input-containers span:before,
        .social-input-containers span:after {
            content: "";
            position: absolute;
            width: 10%;
            opacity: 0;
            transition: 0.3s;
            height: 5px;
            background-color: #d50000;
            top: 50%;
            transform: translateY(-50%)
        }

        .social-input-containers span:before {
            left: 50%
        }

        .social-input-containers span:after {
            right: 50%
        }

        .social-input-containers.focus label {
            top: 0;
            transform: translateY(-50%);
            left: 25px;
            font-size: 0.8rem
        }

        .social-input-containers.focus span:before,
        .social-input-containers.focus span:after {
            width: 50%;
            opacity: 1
        }

        .contact-info {
            padding: 2.3rem 2.2rem;
            position: relative
        }

        .contact-info .title {
            color: black;
        }

        .text {
            color: black;
            margin: 1.5rem 0 2rem 0
        }

        .social-information {
            display: flex;
            color: black;
            margin: 0.7rem 0;
            align-items: center;
            font-size: 0.95rem
        }

        .icon {
            width: 28px;
            margin-right: 0.7rem
        }

        .social-media {
            padding: 2rem 0 0 0
        }

        .social-media p {
            color: #333
        }

        .social-icons {
            display: flex;
            margin-top: 0.5rem
        }

        .social-icons a {
            width: 35px;
            height: 35px;
            border-radius: 43px;
            background-image: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            color: #fff;
            text-align: center;
            line-height: 35px;
            margin-right: 0.5rem;
            transition: 0.3s
        }

        .social-icons a:hover {
            transform: scale(1.05)
        }

        .contact-info:before {
            content: "";
            position: absolute;
            width: 110px;
            height: 100px;
            border: 22px solid blue;
            border-radius: 50%;
            bottom: -77px;
            right: 50px;
            opacity: 0.3
        }

        .social-information i {
            font-size: 22px;
            margin-bottom: 23px;
            margin-right: 8px;
            background-image: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
        }

        .big-circle {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background-image: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            bottom: 50%;
            right: 50%;
            transform: translate(-40%, 38%)
        }

        .big-circle:after {
            content: "";
            position: absolute;
            width: 360px;
            height: 360px;
            background-image: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            border-radius: 50%;
            top: calc(50% - 180px);
            left: calc(50% - 180px)
        }

        .square {
            position: absolute;
            height: 400px;
            top: 50%;
            left: 50%;
            transform: translate(181%, 11%);
            opacity: 0.2
        }
    </style>


    <?php include '../components/navbar.php'; ?>
</head>


<div class="container mt-3 mb-3">
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
                <div class="social-icons"> <a href="#"> <i class="fa-brands fa-facebook-f fa-beat"></i> </a> <a href="#"> <i class="fa-brands fa-x-twitter fa-beat"></i> </a> <a href="#"> <i class="fa-brands fa-instagram fa-beat"></i> </a>
                    <a href="#"> <i class="fa-brands fa-linkedin-in fa-beat"></i></a>
                </div>
            </div>
        </div>
        <div class="contact-info-form"> <span class="circle one"></span> <span class="circle two"></span>
            <form action="control/comentarioCtrl.php" method="post" class="colortext" autocomplete="off">
                <!--  
                    // if (isset($_GET['message'])) {
                    //     $message = $_GET["message"];
                    //     echo '<div class="alert bg-light" role="alert"><strong>' . $message . '</strong></div>';
                    // }
                    -->
                <div class="social-input-containers">
                    <input type="email" name="correo" id="inputblanco" class="input text-light" placeholder="Correo eléctronico">
                </div>
                <!-- <div class="social-input-containers">
                    <select name="asunto" class="input">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option class="text-dark" value="Opinión">Opinión</option>
                        <option class="text-dark" value="Reseña">Reseña</option>
                        <option class="text-dark" value="Sugerencia">Sugerencia</option>
                        <option class="text-dark" value="Pregunta">Pregunta</option>
                        <option class="text-dark" value="Queja">Queja</option>
                    </select>
                </div> -->
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
<?php include '../components/usuaria/footer.php'; ?>
</body>

</html>