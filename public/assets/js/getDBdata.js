const mysql = require('mysql2/promise');

const databaseConfig = {
  "host": "localhost",
  "user": "root",
  "password": "",
  "database": "heal"
};

async function fetchDataForUser(userId, date) {
  const connection = await mysql.createConnection(databaseConfig);

  try {
    const [rows, fields] = await connection.execute(
      'SELECT * FROM your_table_name WHERE user_id = ? AND date = ?',
      [userId, date]
    );

    // Extract dates into an array
    const datesArray = rows.map(row => row.date);

    return datesArray;
  } catch (error) {
    console.error('Error fetching data:', error);
    return [];
  } finally {
    await connection.end();
  }
}

// Example usage
const userId = 3;
const date = '2024-02-24';

fetchDataForUser(userId, date)
  .then(datesArray => {
    console.log('Dates for user:', datesArray);
  })
  .catch(error => {
    console.error('Error:', error);
  });
