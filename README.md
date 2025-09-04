# Advent Of Code

## How to run

Simply run `docker compose run --rm php php ./app.php <year> <day>`.

You can also use `make bash` and then `./app.php <year> <day>`.

To run in test mode, create the `input-test.txt` file in the corresponding folder and run `./app.php --test <year> <day>`

### Notes

This command can automatically retrieve your input from the [Advent of Code](https://adventofcode.com/) website.

First you need to connect to the website to retrieve your "session" cookie and store it in your `.env.local` file.
