async function getWeather() {
  const apiKey = "adb919fc7b60dae9d85ecf13bd9aeb21";
  const location = document.getElementById("locationInput").value;
  const resultDiv = document.getElementById("weatherResult");

  if (!location.trim()) {
    resultDiv.innerHTML = "<p>Please enter a city or ZIP code.</p>";
    return;
  }

  try {
    const response = await fetch(
      `https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(location)}&appid=${apiKey}&units=metric`
    );
    
    if (!response.ok) {
      throw new Error("City not found.");
    }

    const data = await response.json();
    const weather = `
      <h3>${data.name}, ${data.sys.country}</h3>
      <p>🌡️ Temperature: ${data.main.temp}°C</p>
      <p>🌥️ Condition: ${data.weather[0].description}</p>
      <p>💨 Wind: ${data.wind.speed} m/s</p>
    `;

    resultDiv.innerHTML = weather;
  } catch (error) {
    resultDiv.innerHTML = `<p style="color: red;">${error.message}</p>`;
  }
}
