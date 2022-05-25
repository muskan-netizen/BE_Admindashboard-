<html>

<head>
  <title>{{__('My Payment Flow')}}</title>
</head>

<body>
        <div id="pp-button"></div>
      
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://pay.payphonetodoesposible.com/api/button/js?appId={{$payphone_id}}"></script>
<script>

            payphone.Button({
                    //token obtenido desde la consola de developer
                    token:"{{$request->token}}",
        
                            //PARÁMETROS DE CONFIGURACIÓN
                            btnHorizontal: true,
                            btnCard: true,
        
                            createOrder: function(actions){
                                //Se ingresan los datos de la transaccion ej. monto, impuestos, etc
                                return actions.prepare({
        
                                amount: "{{$request->amount}}",
                                amountWithoutTax: "{{$request->amount}}",
                                currency: "USD",
                                clientTransactionId: "{{$request->order_number}}"
                                });
        
                                },
                                onComplete: function(model, actions)
                                {
                                            //Se confirma el pago realizado
                                            actions.confirm({
                                            id: model.id,
                                            clientTxId: model.clientTxId
                                            }).then(function(value){
                                            //EN ESTA SECCIÓN SE RECIBE LA RESPUESTA Y SE MUESTRA AL USUARIO
                                            if (value.transactionStatus == "Approved"){
                                                //alert("Pago " + value.transactionId + " recibido, estado " + value.transactionStatus );
                                              var resUrl = resp.returnUrl+'?id='+value.transactionId+'&clientTransactionId='+resp.orderNo+'&status='+value.transactionStatus;
                                              window.location.href= resUrl;
                                            }
                                        }).catch(function(err){
                                        console.log(err);
                                        });
                                }
                    }).render("#pp-button");

          

</script>
</body>
</html>