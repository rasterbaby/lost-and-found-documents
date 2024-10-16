const express = require('express');
const mysql = require('mysql');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));

// MySQL connection
const db = mysql.createConnection({
    host: 'localhost',
    user: 'yourUsername', // Replace with your MySQL username
    password: 'yourPassword', // Replace with your MySQL password
    database: 'user_db'
});

// Connect to the database
db.connect(err => {
    if (err) {
        throw err;
    }
    console.log('MySQL connected...');
});

// Handle user registration
app.post('/register', (req, res) => {
    const { username, email, password } = req.body;

    // Query to insert the user into the 'users' table
    const query = 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)';
    db.query(query, [username, email, password], (err, results) => {
        if (err) {
            if (err.code === 'ER_DUP_ENTRY') {
                return res.send('Username or email already exists.');
            }
            throw err;
        }
        res.send('User registered successfully!');
    });
});

// Handle user login
app.post('/login', (req, res) => {
    const { username, password } = req.body;

    const query = 'SELECT * FROM users WHERE username = ? AND password = ?';
    db.query(query, [username, password], (err, results) => {
        if (err) throw err;

        if (results.length > 0) {
            res.send('Login successful!');
        } else {
            res.send('Invalid username or password.');
        }
    });
});

// Start the server
app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
