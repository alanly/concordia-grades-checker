# Concordia Grades Checker

## Description

This is a PHP script that I have hacked together, that allows Concordia University students to check their grades and be notified of new grades via email.

This is an extremely roughly put together script that does not even handle authentication. There's nothing in terms of error detection and validation. All usage is at your own risk.

## Configuration

There are two configuration values in the script: `$recordUrl` and `$notifEmail`.

`$recordUrl` should reference the link to your _Student Record_ transcript (i.e. the document in the MyConcordia portal under Academic > Student Record). An example (non-functional link) is included which should help you determine the correct URL. The correct URL can be obtained by checking the source address of the appropriate frame.

`$notifEmail` is simply the email address that notifications should be sent to. The only notification that will be sent is when new grades are found. Email notifications assumes that you have PHP appropriately configured for the `mail` command.

Further more, the script will create two files, one holding the response page and another to keep track of the number of grades. Therefore, the directory the script it run from needs to be writable by the user.

## Usage

The script can be simply called from the command line (i.e. `php grades.php`) and will output all your registered and completed courses along with their grades. Additionally, the script will tally up the total number of grades you have and the number that's missing.

If you would like to automate the script, you can simply create a cronjob to run it at a specific interval.

## License

The code is licensed under the [MIT license](http://opensource.org/licenses/MIT).
