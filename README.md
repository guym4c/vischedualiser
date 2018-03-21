# vischedualiser
Visualises output from our OS coursework at Sussex

This is very quick'n'dirty. Please excuse the code. And it doesn't really like any nasty input, but I decided to get on the actual coursework. Pull requests and issues welcome.

### POST /view
Post the command output as `command` and receive back a nicely formatted CPU timeline.

### POST /view/?realistic
As above but default to realistic timeline block width with no minimum value. 

### POST /view/?debug
Post the command output as `command` and receive unformatted scheduler events.

### POST /view/?json
Post the command output as `command` and receive scheduler events as JSON.
