# concordia-grades-checker

This script simply checks for new grades and emails you notifications if needed.

## Usage

As always, this script can be setup in a cronjob, in order to have it run in intervals. And the script must be able to create files in its calling/starting path.

There are two ways of using the new-and-improved implementation:

### 1. Configuring

You can edit the script and add your details to the necessary fields, specifically:

    $netname
    $password
    $email

Once done, you can then run the script via `php check-grades.php`. This will then echo a "new grades" message if there are any, and email you a notification if possible.

### 2. Command arguments

Rather than editing the script, you can simply call it from the command-line with the appropriate arguments:

    php check-grades.php "netname" "password" "email@address"

Remember to put quotes around the values, otherwise you may end up with some funky errors because of symbols. Like before, you will get a "new grades" message and an email notification, if applicable and possible.

## License

This script is licensed under the [MIT license](http://opensource.org/licenses/MIT).

