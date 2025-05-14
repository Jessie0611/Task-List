<?php include("database.php"); 
$apiKey = "adb919fc7b60dae9d85ecf13bd9aeb21";
?>
<?php include("header.php"); ?>

<main class="weather-container">
  <section class="weather-card">
  <header class="page-header">
  <h1 class="weather-title">🌦️ Weather <span class="highlight"> Checker</span></h1>
  <p class="subtitle">Check todays weather for your city to help you plan task!</p>

  </header>
    <div class="weather-form">
      <input type="text" id="locationInput" placeholder="Enter City Name" aria-label="Location input"/>
      <button onclick="getWeather()">Get Weather</button>
    </div>
    <div id="weatherResult" class="weather-result"></div>
  </section>
</main>

<script src="script.js"></script>
</body>
</html>
