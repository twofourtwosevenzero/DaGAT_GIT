DaGAT System Seeder Setup Guide

Follow these steps to get the application up and running smoothly with seeded data.

Prerequisites

Before running the seeders, ensure you have completed the following steps:

Clone the Repository: Clone the DaGAT System repository to your local machine.

Install Dependencies: Run composer install to install all Laravel dependencies.

Environment Configuration: Set up your .env file by copying the .env.example file, configuring the database credentials, and other required environment variables.


Database Setup

Migrate Database: Before seeding, you need to migrate the database tables. Run the following command to create the database structure:

'php artisan migrate'

Running Seeders: The following seeders are used to populate the tables with initial data that is essential for the proper functioning of the system.

Seeders Overview

DatabaseSeeder.php: This is the main seeder that calls all other seeders to populate the database. It acts as the entry point to run all the necessary seeding operations.

DocumentTypeSignatoriesSeeder.php: Seeds predefined signatories for each document type to document_type_signatories table. This ensures that when a document type is created, the correct signatories are assigned automatically.

DocumentTypesTableSeeder.php: Populates the document_types table with the various types of documents that can be created in the system.

OfficesTableSeeder.php: Adds records for all offices involved in processing documents. This includes administrative offices, services, colleges, and local councils.

PositionsTableSeeder.php: Seeds the different positions for each office, such as Manager, Clerk, Director, etc.

PrivilegesTableSeeder.php: Populates the privileges table with the different roles and access levels for the system users.

StatusesTableSeeder.php: Inserts the different possible statuses for documents, including Pending, Approved, In Review, and Deleted.

UsersTableSeeder.php: Seeds initial users into the system, which may include admin users and regular office users with varying roles.

Running the Seeders

To run all the seeders, execute the following command:

'php artisan db:seed'

This command will execute the DatabaseSeeder.php, which will in turn run all other seeders in the correct order to populate the database tables.

If you need to run a specific seeder, you can do so by specifying the seeder name, for example:

'php artisan db:seed --class=DocumentTypesTableSeeder'

Important Notes

Data Overwrite Warning: Running seeders on an existing database might overwrite current data, especially if the seeders include truncate statements. Make sure to back up any critical data before reseeding.

Database Reset: If you need to reset the database and seed again, you can run:

php artisan migrate:refresh --seed

This command will rollback all migrations, re-run them, and then execute the seeders.

Troubleshooting

Migration Errors: Ensure that your database connection is properly configured in the .env file. If you encounter errors during migration or seeding, check the Laravel logs in storage/logs/ for more details.

Seeder Not Found: Make sure the class name in your --class parameter matches exactly the seeder class name, including capitalization.

Conclusion

Once you have run all the seeders, your database will be populated with the necessary initial data to use the DaGAT System. You can now proceed to run the application and test its features.

If you have any questions or issues while setting up, feel free to reach out!

