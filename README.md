# Advent Of Code

## How to run

First you need to build the image with `castor build`, then you can simply run `castor run <year> <day> [--test|-t]`.

You can also use `castor bash` and then `./app.php <year> <day> [--test|-t]`.

### Notes

This command can automatically retrieve your input from the [Advent of Code](https://adventofcode.com/) website.

First you need to connect to the website to retrieve your "session" cookie and store it in your `.env.local` file.
