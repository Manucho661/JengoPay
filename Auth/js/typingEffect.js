export async function typingEffect() {
    const textElement = document.getElementById("typeText");
    const text = textElement.innerHTML;
    textElement.innerHTML = ""; // Clear the text initially

    let currentIndex = 0;
    let isTyping = true;
    const typingSpeed = 100; // Speed of typing in ms
    const pauseTime = 1500; // Time to pause before erasing

    // Function to type the text letter by letter
    function typeText() {
        if (currentIndex < text.length) {
            textElement.innerHTML += text[currentIndex];
            currentIndex++;
            setTimeout(typeText, typingSpeed);
        } else {
            setTimeout(eraseText, pauseTime);  // Wait for a moment before erasing
        }
    }

    // Function to erase the text letter by letter
    function eraseText() {
        if (currentIndex > 0) {
            textElement.innerHTML = textElement.innerHTML.slice(0, -1);
            currentIndex--;
            setTimeout(eraseText, typingSpeed); // Erase at the same speed
        } else {
            setTimeout(typeText, pauseTime); // After erasing, start typing again
        }
    }

    typeText();  // Start the typing effect 
}