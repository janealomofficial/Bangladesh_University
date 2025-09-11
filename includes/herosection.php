<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hero Section</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;900&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
    }

    .hero-section {
      background: url('assets/images/graduation.png') no-repeat center center/cover;
      position: relative;
      color: white;
      text-align: center;
      padding: 120px 20px;
    }

    .hero-section::before {
      content: '';
      position: absolute;
      inset: 0;
      background-color: rgba(0, 18, 51, 0.85); /* Overlay */
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 900px;
      margin: 0 auto;
    }

    .hero-subheadline {
      font-size: 1.5rem;
      font-weight: 700;
    }

    .hero-title {
      font-size: 5rem;
      font-weight: 900;
      line-height: 1.1;
    }

    .hero-description {
      font-size: 1.25rem;
      font-style: italic;
      margin-top: 25px;
    }

    @media (max-width: 768px) {
      .hero-title {
        font-size: 3rem;
      }

      .hero-subheadline {
        font-size: 1.2rem;
      }

      .hero-description {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

  <section class="hero-section d-flex align-items-center justify-content-center">
    <div class="hero-content text-center">
      <p class="hero-subheadline">Get ready for an education that's</p>
      <h1 class="hero-title">EVERYTHING<br>YOU NEED</h1>
      <p class="hero-description">…and more than you could ever have imagined.</p>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
