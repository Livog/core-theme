<!DOCTYPE html>
<html>
<head>
  <title>Nothing Here :)</title>
  <meta name="robots" content="noindex,nofollow">
  <link href="https://fonts.googleapis.com/css?family=Poppins:600" rel="stylesheet">
  <style>
    html,body {
      margin: 0;
      background-color: #6381FE;
      font-family: 'Poppins', sans-serif;
      height: 100%;
      width: 100%;
      color: #fff;
    }
    .w-100 {
      width: 100%;
    }
    .h-100 {
      height: 100%;
    }
    .text-center {
      text-align: center;
    }
    .d-flex {
      display: flex;
    }
    .justify-content-center {
      justify-content: center;
    }
    .align-items-center {
      align-items: center;
    }
    .button {
      border-radius: 1000px;
      background-color: #63d376;
      display: inline-block;
      padding: 15px 20px;
      color: #fff;
      text-decoration: none;
      transition: all 280ms ease;
    }
    .button:hover {
      background-color: #49cc5f;
    }
    .container {
      max-width: 600px;
    }
  </style>
</head>
<body>
  <div class="d-flex w-100 h-100 justify-content-center align-items-center">
    <div class="container text-center">
      <img src="<?= THEME_URI . '/assets/images/coffee-list.svg' ?> " />
      <h1>This is just an API</h1>
      <h3>This site is using the core theme, which is just a team that makes your Wordpress site work as a content API.</h3>
      <a href="<?= get_field('base_url', 'option'); ?>" class="button">The real site is here</a>
    </div>
  </div>
</body>
</html>