import mysql.connector
import random
import string

# Function to generate a random string of fixed length
def generate_random_string(length=15):
    return ''.join(random.choices(string.ascii_letters + string.digits, k=length))

# Connect to the MariaDB database
connection = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',  # Update with your database password
    database='checkbox_db'
)

cursor = connection.cursor()

# Prepare the SQL statement for inserting data
insert_stmt = """
INSERT INTO checkboxes (id)
VALUES (%s)
"""

# Number of checkboxes to insert
num_checkboxes = 100000

# Loop to generate and insert checkboxes
for _ in range(num_checkboxes):
    checkbox_id = generate_random_string()  # Generate a random checkbox_id

    # Execute the insert statement with only the checkbox_id
    cursor.execute(insert_stmt, (checkbox_id,))

    # Commit every 1000 inserts to avoid excessive memory usage
    if _ % 1000 == 0:
        connection.commit()
        print(f'Inserted {_} checkboxes')

# Commit any remaining inserts
connection.commit()

# Close the cursor and connection
cursor.close()
connection.close()

print('Finished inserting 100,000 checkboxes.')
