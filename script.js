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

/* - - - - - - - - - - Practice Questions - - - - - - - - - - */
/*🧠 Basic Logic & Variables
Swap Two Variables: Write a program that swaps the values of two variables.
JavaScript*/
let a = 5;
let b = 10;

let temp = a;
a = b;
b = temp;

console.log("a:", a, "b:", b);
/*Java
public class swapExample {
    public static void main(String[] args) {
        int a = 5, b = 10;
        int temp = a;
        a = b;
        b = temp;
        System.out.println("a: " + a + " b: " + b);
  }      
 }*/

/*Even or Odd: Ask the user for a number and print whether it’s even or odd.*/
let num = prompt("Enter a number: ");
num = Number(number);

if (number % 2 === 0) {
    console.log("Even");
} else {
    console.log("Odd");
}
/*Java
public class EvenOdd {
    public static void main(String[] args) {
        int num = 7;
        if (num % 2 == 0) {
            System.out.println("Even");
        } else {
            System.out.println("Odd");
        }
    }
}
 */

/*Maximum of Two Numbers: Input two numbers and print the bigger one.*/
let number1 = prompt("Enter a number: ");
let number2 = prompt("Enter another number: ");

number1 = Number(number1); // convert from string to int
number2 = Number(number2);

if(number1 < number2) {
  console.log(number2 + " is larger than " + number1);
}else if(number1 > number2) {
  console.log(number1 + " is larger than " + number2);
}else{
  console.log("Both numbers are equal.");
}



/*Loops & Conditionals 
Print 1-10: Use a loop to print numbers from 1 to 10.
JavaScript*/
for (let i = 1; i <= 10; i++) {
    console.log(i);
}
/*Java
public class printNum{
    public static void main(String[] args) {
        for (int i = 1; i <= 10; i++) {
            System.out.println(i);
        }
    }
}
 */



Multiplication Table
Ask the user for a number and print its multiplication table up to 10.



Sum of N Numbers
Ask for a number n, then sum all numbers from 1 to n.



/*Reverse a String: Ask for a string and print it in reverse. 
JavaScropt*/
let userInput = prompt("Enter a string: ");
let reversed = str.split("").reverse().join("");
console.log(reversed);




/*Count Vowels: Count how many vowels are in a given string.*/

/*Palindrome Checker: Check if a word is the same forward and backward (e.g., "level").*/

/*Find the Largest Number in a List: Given a list of numbers, find and print the largest one.*/
let numbers = [4, 7, 1, 9, 3];
let max = Math.max(...numbers);
console.log("Largest:", max);

/*Java
public class MaxNumber {
    public static void main(String[] args) {
        int[] numbers = {4, 7, 1, 9, 3};
        int max = numbers[0];
        for (int num : numbers) {
            if (num > max) {
                max = num;
            }
        }
        System.out.println("Largest: " + max);
    }
} */



/*Count Even Numbers in a List: Count how many even numbers are in a list.*/

/*Sum All Elements in a List: Given a list, return the total sum of all elements.*/