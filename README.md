# Json 2 DTO

[Spatie's Data Transfer Object](https://github.com/spatie/data-transfer-object) library is awesome, but typing out DTOs
can quicky become a chore. Inspired by Json2Typescript style tools, I built one for PHP DTOs.

Try it out at https://json2dto.atymic.dev

[![](https://repository-images.githubusercontent.com/231837839/14ed4680-2fb2-11ea-81fe-f06c5038b0dd)](https://json2dto.atymic.dev)

## How it works

It's a pretty simple application. There's a vue frontend which provides the interface, and interacts with a simple API.
The API (running on serverless 😎) takes the decoded json, figures out the types for each field and then generates
a PHP DTO class, based on the selected options.

## Improvements

- [ ] Investigate supporting generating nested DTOs
