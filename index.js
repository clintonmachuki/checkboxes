// Function to load and display chat messages
async function loadChat() {
    const chatDiv = document.getElementById('chat');
    try {
        const response = await fetch('chat.php');
        const messages = await response.json();
        chatDiv.innerHTML = ''; // Clear existing messages
        messages.forEach(msg => {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message';
            messageDiv.innerHTML = `<span>${msg.username}:</span> ${msg.message}`;
            chatDiv.appendChild(messageDiv);
        });
        chatDiv.scrollTop = chatDiv.scrollHeight; // Scroll to bottom
    } catch (error) {
        console.error('Error loading chat messages:', error);
    }
}

// Function to load and display checkboxes
const perPage = 3000;
let currentPage = 1; // Initialize currentPage to 1


// Function to load and display checkboxes
// Function to load and display checkboxes
async function loadCheckboxes(page) {
    const checkboxesDiv = document.getElementById('checkboxes');
    try {
        const response = await fetch(`checkboxes.php?page=${page}`);
        const checkboxes = await response.json();
        checkboxesDiv.innerHTML = ''; // Clear existing checkboxes
        checkboxes.forEach(checkbox => {
            const box = document.createElement('input');
            box.type = 'checkbox';
            box.checked = checkbox.is_checked === 1;
            box.dataset.id = checkbox.id;

            // Apply the user's color for their checkboxes
            fetch('get_user_color.php') // Fetch user color via an AJAX request
                .then(response => response.text())
                .then(color => {
                    box.style.backgroundColor = color; // Set user's color
                });

            box.style.border = '2px solid #000'; // Add border for visibility
            box.style.margin = '2px'; // Add margin for spacing
            box.addEventListener('change', () => {
                fetch('checkboxes.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `box_id=${box.dataset.id}&is_checked=${box.checked ? 1 : 0}`
                }).catch(error => console.error('Error updating checkbox:', error));
            });
            checkboxesDiv.appendChild(box);
        });
    } catch (error) {
        console.error('Error loading checkboxes:', error);
    }
}

// Function to get a random color
function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Pagination controls
document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        loadCheckboxes(currentPage); // Load the previous page
    }
});

document.getElementById('nextPage').addEventListener('click', () => {
    currentPage++;
    loadCheckboxes(currentPage); // Load the next page
});

// Function to handle chat form submission
document.getElementById('chatForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const messageInput = document.getElementById('chatMessage');
    const message = messageInput.value;

    try {
        await fetch('chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `username=${encodeURIComponent(username)}&message=${encodeURIComponent(message)}`
        });
        messageInput.value = ''; // Clear input field
        loadChat(); // Reload chat messages
    } catch (error) {
        console.error('Error sending chat message:', error);
    }
});

// Initial load
loadChat();
loadCheckboxes();

// Set intervals to refresh chat and checkboxes, keeping current page in mind
setInterval(loadChat, 5000); // Refresh chat every 5 seconds
setInterval(() => loadCheckboxes(currentPage), 400); // Refresh checkboxes every 20 seconds, maintaining the current page
