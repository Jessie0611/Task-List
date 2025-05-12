function getWeather() {
    const location = document.getElementById('locationInput').value.trim();
    const apiKey = 'a1fc34767b612ebf210ee36d5cd32242';
  
    if (!location) {
      alert('Please enter a city or ZIP code.');
      return;
    }
  
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${location}&appid=${apiKey}&units=imperial`;
  
    fetch(url)
      .then(response => {
        if (!response.ok) {
        }
        return response.json();
      })
      .then(data => {
        const temp = data.main.temp;
        const description = data.weather[0].description;
        const city = data.name;
  
        document.getElementById('weatherResult').innerHTML =
          `<p><strong>${city}</strong></p>
           <p>${description}</p>
           <p>🌡️ ${temp}°F</p>`;
      })
      .catch(error => {
        document.getElementById('weatherResult').innerHTML = `<p style="color:red;">${error.message}</p>`;
        throw new Error('Weather not found');
      });
  }
  