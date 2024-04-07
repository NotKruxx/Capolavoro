CREATE DATABASE Capolavoro;

USE Capolavoro;

CREATE TABLE UserCredentials (
    UserID INTEGER AUTO_INCREMENT PRIMARY KEY,
    Username TEXT NOT NULL,
    Email TEXT NOT NULL UNIQUE,
    PasswordHash TEXT NOT NULL
);

CREATE TABLE Transactions (
    TransactionID INTEGER PRIMARY KEY AUTO_INCREMENT,
    UserID INTEGER NOT NULL,
    Description TEXT,
    Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Amount REAL,
    Tipo TEXT,
    FOREIGN KEY (UserID) REFERENCES UserCredentials(UserID)
);
