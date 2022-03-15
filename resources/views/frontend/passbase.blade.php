<!DOCTYPE html>
<html>
  <head>
    <script type="text/javascript" src="https://unpkg.com/@passbase/button"></script>
    <link rel="stylesheet" href="index.css" />
    <title>Verify your Identity</title> 
  </head>

  <body>
    <img
      class="img-fluid passbase"
      src="https://passbase.com/assets/images/logo.png"
      alt="Passbase"
    />

    <div class="container">
      <p class="title">Verify your identity now</p>
      <p class="subtitle">You can verify your identity by clicking the verification button below.</p>

      <!-- 1. This is the Passbase Component -->
      <div id="passbase-button"></div>
    </div>

    <script type="text/javascript">
      // This is the logic for the Passbase component
      const element = document.getElementById("passbase-button");
      // update the below variable with your own publishable API key**your, which you can find in the [API settings](https://app.passbase.com/settings/api) section.
      const apiKey = "ILVcVB4OWqBus0Clk4bC2PJhpQArmUQ3LVHB2L1iD4YQxB7gxlngCebUakHoZA9o";
      Passbase.renderButton(element, apiKey, {
        // Speed up the verification flow by providing some information you might already have like the user's email to skip the email step
        prefillAttributes: {
          email: "sujatacodebrew@gmail.com",
          country: "in"
        },
        onSubmitted: (identityAccessKey) => {
          console.log('-----------------On Submit--------------------');
          console.log(identityAccessKey);

        },
        onFinish: (identityAccessKey) => {
          console.log('-----------------On Finish--------------------');
            console.log(identityAccessKey);
          // Do what you want after the flow finished
          sendAuthKeyToBackend(identityAccessKey);
          // window.location.href =("https://sales.alerthire.com");
        },
        onError: (errorCode) => {},
        onStart: () => {},
      });

      // Optional - Example to send identity access key to your backend
      const sendAuthKeyToBackend = (identityAccessKey) => {  
        const body = {
          identityAccessKey: identityAccessKey,
        };
        const requestOptions = {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(body),
        };
        fetch("{{route('passbase.store')}}", requestOptions)   
          .then((response) => {
            console.log("Success");
            console.log(response);
          })
          .catch((error) => {
            console.log(error);
          });
      };
    </script>
  </body>
</html>