# BrightDSMS
Digital Signage Management System

---

BrightDSMS is a basic and discrete digital signage management system designed to remotely synchronize and update content across an unlimited amount of browser instances. BrightDSMS allows for administrators to manage content across multiple endpoints individually via unique URLs assigned to each endpoint. Administrators also have the ability to remotely refresh any browser instance initialized on each respective endpoint to ensure up-to-date content is displayed accurately across all browser instances.

---

## Getting started:

1. Utilizing an Apache2 + PHP enabled web server, clone this repository to your `/var/www/html/` directory and give your system recursive read/write/execute permissions to the folder.

2. Enable .htaccess overrides in your apache2.config

    1. Change `AllowOverride None` to `AllowOverride All` found inside `/etc/apache2/apache2.conf`  

        ```
        <Directory /var/www/>
            AllowOverride All
            Require all granted
        </Directory>
        ```

    2. Restart apache2

3. Inside of `/var/www/html/BrightDSMS/` create and grant your system read/write/execute permissions to `endpoints.json` and use the following basic JSON formatting to create and edit your endpoints. Create a `"404"` entry to control where clients should be redirected in the event that one attempts to access an endpoint that does not exist. If you do not create this endpoint, clients will simply be greeted with a black screen.

```json
{
  "404": "https://your404URLhere.example/",
  
  "endpoint_name": "https://yourURLhere.example/",
  "endpoint_name2": "https://yourURLhere2.example/"
}
```

> Note: For some websites such as published Google Slide links and/or other websites that may include unwanted UI elements, you may want to attach `&rm=minimal` to the end of the URL to remove them and just display the body content of the website itself.

## Configuring clients:

1. Using any browser, point your client to [http://[Your Server]/BrightDSMS/endpoint\_name](<http://%5BYour%20Server%5D/BrightDSMS/endpoint_name>) and verify that your endpoint.json file creation was successful.

## Refreshing clients:

1. Using any browser, visit [http://[Your Server]/BrightDSMS/trigger-refresh.php?sn=endpoint\_name](<http://%5BYour%20Server%5D/BrightDSMS/trigger-refresh.php?sn=endpoint_name>), upon loading this page you will be greeted with a screen displaying the endpoint name provided in the URL and a button to manually trigger the refresh. Upon clicking the button a refresh will be sent out to all clients listening on the respective endpoint stated in `?sn=endpoint_name` at the end of the url. Press the button again, or modify the URL to a new endpoint to refresh again. Refreshes take approximately 6 seconds to give all clients adequate time to catch the refresh call.

