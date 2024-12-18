<?php
$price = 0.0015;
$destino = "0xF0A48ca3285D425c7441901d2efC533A4592aD14";
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>BITBET Premier - betting and gambling at web3</title>
    <!-- /SEO Ultimate -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="57x57" href="./assets/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="./assets/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="./assets/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="./assets/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="./assets/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="./assets/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="./assets/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="./assets/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="./assets/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="./assets/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="./assets/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Latest compiled and minified CSS -->
    <link href="assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="./assets/js/bootstrap.min.js">
    <!-- Font Awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- StyleSheet link CSS -->
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/special_classes.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/mediaqueries.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/owl.carousel.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/owl.theme.default.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css"/>
    <script src="https://cdn.jsdelivr.net/npm/web3/dist/web3.min.js"></script>
    <script>
        window.addEventListener('load', () => {
            let web32;
            let accounts = [];
            const inputBNB = document.getElementById('bnb-amount');
            const outputTokens = document.getElementById('tokens-amount');
            const usdcWbnbPairAddress = '0xd99c7F6C65857AC913a8f880A4cb84032AB2FC5b';
            const pairABI = [
                {
                    "constant": true,
                    "inputs": [],
                    "name": "getReserves",
                    "outputs": [
                        { "internalType": "uint112", "name": "reserve0", "type": "uint112" },
                        { "internalType": "uint112", "name": "reserve1", "type": "uint112" },
                        { "internalType": "uint32", "name": "blockTimestampLast", "type": "uint32" }
                    ],
                    "payable": false,
                    "stateMutability": "view",
                    "type": "function"
                }
            ];

            async function connectWallet() {
                if (window.ethereum) {
                    web32 = new Web3(window.ethereum);
                    try {
                        accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                        alert('Conectado à carteira: ' + accounts[0]);
                        document.getElementById("btn-connect").innerHTML = accounts[0];
                        document.getElementById("btn-connect").style.width = "90%";
                        document.getElementById("btn-connect").style.fontSize = "14px";
                    } catch (error) {
                        alert('Usuário recusou a conexão:', error);
                    }
                } else if (window.web32) {
                    web32 = new Web3(window.web3.currentProvider);
                    accounts = await web32.eth.getAccounts();
                    alert('Conectado via web3:', accounts[0]);
                } else {
                    alert('Nenhum provedor Ethereum injetado encontrado.');
                }
            }

            document.getElementById('btn-connect').addEventListener('click', connectWallet);

                        async function getBNBPrice() {
                                if (!web32) {
                                    console.log('Wallet not connected');
                                    return 0;
                                }
                            
                                try {
                                    const pairContract = new web32.eth.Contract(pairABI, usdcWbnbPairAddress);
                                    const reserves = await pairContract.methods.getReserves().call();
                                    // Assumindo que reserve0 e reserve1 são retornados como BigInt, converta para número flutuante
                                    const reserve0 = parseFloat(web32.utils.fromWei(reserves.reserve0, 'ether'));
                                    const reserve1 = parseFloat(web32.utils.fromWei(reserves.reserve1, 'ether'));
                                    const bnbPriceInUSDC = reserve0 / reserve1;
                                    return bnbPriceInUSDC;
                                } catch (error) {
                                    console.error('Error fetching BNB price:', error);
                                    return 0;
                                }
                            }


            async function updateTokenEstimate() {
                        if(inputBNB.value>=0.01){
                             if (!web32) {
                                console.log('Wallet not connected');
                                return;
                            }
                        
                            try {
                                const bnbAmount = parseFloat(inputBNB.value) || 0;
                                const bnbPriceInUSDC = await getBNBPrice();
                                // Suponha que o tokenPriceInUSDC seja 0.5 USD, ajuste conforme necessário
                                const tokenPriceInUSDC = <?php print $price;?>;
                        
                                // Certifique-se de que todas as variáveis estão em formato flutuante para cálculo
                                const tokens = (bnbAmount * bnbPriceInUSDC) / tokenPriceInUSDC;
                                outputTokens.value = tokens.toFixed(4);
                            } catch (error) {
                                console.error('Error updating token estimate:', error);
                            }
                        }else{
                            outputTokens.value = "input min 0.01 BNB"
                        }
                   
                }
                
            
                
                
            async function buyTokens() {
                        if (!web32 || accounts.length === 0) {
                            alert('Por favor, conecte sua carteira primeiro.');
                            return;
                        }
                    
                        const amountBNB = document.getElementById('bnb-amount').value;
                        if (!amountBNB || parseFloat(amountBNB) <= 0) {
                            alert('Por favor, insira uma quantidade válida de BNB.');
                            return;
                        }
                        
                        const destinationAddress = '<?php print $destino;?>'; // Substitua pelo endereço de destino real
                        
                       try {
        const amountWei = BigInt(web32.utils.toWei(amountBNB, 'ether'));
        const gasPrice = BigInt(await web32.eth.getGasPrice());
        const gasLimit = BigInt(21000); // Um valor de gasLimit que você acha que será suficiente

        // Calcula o custo máximo de gas para a transação como BigInt
        const gasCost = gasPrice * gasLimit;

        // Verifica se a conta tem saldo suficiente para cobrir o valor da transação mais o gas
        const balance = BigInt(await web32.eth.getBalance(accounts[0]));
        if (balance < amountWei + gasCost) {
            alert('Saldo insuficiente para cobrir o valor e o gas.');
            return;
        }

        const transactionParameters = {
            to: destinationAddress,
            from: accounts[0],
            value: '0x' + amountWei.toString(16),
            gasPrice: '0x' + gasPrice.toString(16),
            gas: '0x' + gasLimit.toString(16)
        };

        const txHash = await web32.eth.sendTransaction(transactionParameters);
        console.log('Transação bem sucedida:', txHash);


                            $( "#result" ).load( `./success.php?hash=${txHash.transactionHash}` );
                           // window.location.href = `https://alion.network/bitbet;
                        } catch (error) {
                            console.error('Erro ao comprar tokenss:', error);
                            alert('Transação falhou!.');
                        }
                    }
                    
                    document.getElementById('btn-buy').addEventListener('click', buyTokens);


            setInterval(updateTokenEstimate, 3000);
        });
        
                        async function vendidos() {
                            $.get('./sold.php', function(data) {
                                const vendidos = parseFloat(data);
                                const totalTokens = 172000000; // Total de tokens à venda
                                const percentualVendido = (vendidos / totalTokens) * 100;
                        
                                $('#vendidos').text(vendidos.toLocaleString()); // Atualiza o texto com o número formatado
                                $('.progress-bar').css('width', percentualVendido + '%').text(percentualVendido.toFixed(2) + '%'); // Atualiza a barra de progresso
                            });
                        }

// Chame vendidos a cada intervalo de tempo, por exemplo, a cada 3 segundos
setInterval(vendidos, 5000);
    </script>
</head>

<body>
<div class="banner-section-outer">
    <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="./index.html"><figure class="mb-0"><img src="./assets/images/crox_logo.png" alt=""></figure></a>
                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="https://bitbetpremier.io/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://bitbetpremier.io/about.html">About</a>
                        </li>
                        <li class="m-0">
                            <a class="navbar-brand" href="https://bitbetpremier.io/index.html"><figure class="mb-0"><img src="./assets/images/crox_logo.png" alt=""></figure></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://bitbetpremier.io/tokenomics.html">Tokenomics</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://bitbetpremier.io/team.html">Team</a>
                        </li>
                        <!--<li class="nav-item">
                            <a class="nav-link" href="./match_detail.html">Detail</a>
                        </li>
                        <li class="nav-item mr-0">
                            <a class="nav-link" href="./contact.html">Contact</a>
                        </li>-->
                        <li class="nav-item mr-1 ml-0">
                            <a class="nav-link fa-brands fa-telegram" href="https://t.me/BitBetPremier"></a>
                        </li>
                        <li class="nav-item mr-1 ml-0">
                            <a class="nav-link fa-brands fa-twitter" href="https://twitter.com/bitbetpremier"></a>
                        </li>
                        <li class="nav-item mr-1 ml-0">
                            <a class="nav-link fa-brands fa-medium" href="https://medium.com/@bitbetpremier"></a>
                        </li>
                        <!--<li class="nav-item mr-1 ml-0">
                            <a class="nav-link login_btn" href="./login.html">Log in</a>
                        </li>
                        <li class="nav-item ml-0">
                            <a class="nav-link signup_btn" href="./signup.html">Sign Up</a>
                        </li>-->
                    </ul>
                </div>
            </nav>
        </div>
        <style type="text/css">
                .form-style-5{
                	max-width: 500px;
                	padding: 10px 20px;
                	background: #f4f7f8;
                	margin: 10px auto;
                	padding: 20px;
                	background: #f4f7f8;
                	border-radius: 8px;
                	font-family: Georgia, "Times New Roman", Times, serif;
                }
                .form-style-5 fieldset{
                	border: none;
                }
                .form-style-5 legend {
                	font-size: 1.4em;
                	margin-bottom: 10px;
                }
                .form-style-5 label {
                	display: block;
                	margin-bottom: 8px;
                }
                .form-style-5 input[type="text"],
                .form-style-5 input[type="date"],
                .form-style-5 input[type="datetime"],
                .form-style-5 input[type="email"],
                .form-style-5 input[type="number"],
                .form-style-5 input[type="search"],
                .form-style-5 input[type="time"],
                .form-style-5 input[type="url"],
                .form-style-5 textarea,
                .form-style-5 select {
                	font-family: Georgia, "Times New Roman", Times, serif;
                	background: rgba(255,255,255,.1);
                	border: none;
                	border-radius: 4px;
                	font-size: 15px;
                	margin: 0;
                	outline: 0;
                	padding: 10px;
                	width: 100%;
                	box-sizing: border-box; 
                	-webkit-box-sizing: border-box;
                	-moz-box-sizing: border-box; 
                	background-color: #e8eeef;
                	color:#8a97a0;
                	-webkit-box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
                	box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
                	margin-bottom: 30px;
                }
                .form-style-5 input[type="text"]:focus,
                .form-style-5 input[type="date"]:focus,
                .form-style-5 input[type="datetime"]:focus,
                .form-style-5 input[type="email"]:focus,
                .form-style-5 input[type="number"]:focus,
                .form-style-5 input[type="search"]:focus,
                .form-style-5 input[type="time"]:focus,
                .form-style-5 input[type="url"]:focus,
                .form-style-5 textarea:focus,
                .form-style-5 select:focus{
                	background: #d2d9dd;
                }
                .form-style-5 select{
                	-webkit-appearance: menulist-button;
                	height:35px;
                }
                .form-style-5 .number {
                	background: #1abc9c;
                	color: #fff;
                	height: 30px;
                	width: 30px;
                	display: inline-block;
                	font-size: 0.8em;
                	margin-right: 4px;
                	line-height: 30px;
                	text-align: center;
                	text-shadow: 0 1px 0 rgba(255,255,255,0.2);
                	border-radius: 15px 15px 15px 0px;
                }
                
                .botaozin {
                    position: relative;
                	display: block;
                	padding: 19px 39px 18px 39px;
                	color: #FFF;
                	margin: 0 auto;
                	background: #1abc9c;
                	font-size: 18px;
                	text-align: center;
                	font-style: normal;
                	width: 100%;
                	border: 1px solid #16a085;
                	border-width: 1px 1px 3px;
                	margin-bottom: 10px;
                }
                
                .form-style-5 input[type="submit"],
                .form-style-5 input[type="button"]
                {
                	position: relative;
                	display: block;
                	padding: 19px 39px 18px 39px;
                	color: #FFF;
                	margin: 0 auto;
                	background: #1abc9c;
                	font-size: 18px;
                	text-align: center;
                	font-style: normal;
                	width: 100%;
                	border: 1px solid #16a085;
                	border-width: 1px 1px 3px;
                	margin-bottom: 10px;
                }
                .form-style-5 input[type="submit"]:hover,
                .form-style-5 input[type="button"]:hover
                {
                	background: #109177;
                }
                
                
                
.progress-bar {
  height: 16px;
  border-radius: 4px;
	background-image: -webkit-linear-gradient(top, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.05));
  background-image: -moz-linear-gradient(top, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.05));
  background-image: -o-linear-gradient(top, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.05));
  background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.05));
  -webkit-transition: 0.4s linear;
  -moz-transition: 0.4s linear;
  -o-transition: 0.4s linear;
  transition: 0.4s linear;
  -webkit-transition-property: width, background-color;
  -moz-transition-property: width, background-color;
  -o-transition-property: width, background-color;
  transition-property: width, background-color;
  -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.25), inset 0 1px rgba(255, 255, 255, 0.1);
  box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.25), inset 0 1px rgba(255, 255, 255, 0.1);
}
</style>
    </header>
<!-- SOCIAL ICONS
    <div class="left_icons float-left d-table" data-aos="fade-down">
        <div class="icon_content d-table-cell align-middle">
            <ul class="list-unstyled p-0 m-0">
                <li>
                    <a href="https://medium.com/@bitbetpremier"><i class="fa-brands fa-telegram" aria-hidden="true"></i></a>
                </li>
                <li>
                    <a href="https://twitter.com/bitbetpremier"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a>
                </li>
                <li>
                    <a href="https://medium.com/@bitbetpremier"><i class="fa-brands fa-medium" aria-hidden="true"></i></a>
                </li>
            </ul>
        </div>
    </div> -->
<!-- BANNER SECTION -->
    <section class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 text-lg-left text-center">
                    <div class="banner-section-content">



                    <div class="form-style-5">
                        Token Price: $<?php print $price;?><br>
                        Sold: <span id="vendidos">loading...</span> / 172,000,000
                        <div class="sold" style="background: grey; border-radius: 2px">
                          <div class="progress-bar" style="width:5%; color: black">loading...%</div>
                        </div><br>
                        <div id="result"></div>
                        <legend><span class="number">1</span> Connect to BSC</legend>
                    <button id="btn-connect">Connect Wallet</button>
                    <form>
                        <legend><span class="number">2</span> BNB amount</legend>
                        <input type="number" id="bnb-amount" step="0.01" min="0.01">
                        <legend><span class="number">3</span> You will receive (Bitbet):</legend>
                        <input type="text" id="tokens-amount" readonly>
                        <legend><span class="number">4</span> Get tokens!!!</legend>
                        <button class="botaozin" type="button" id="btn-buy" >Buy Tokens</button>
                    </form>
                    </div>



                        
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12"></div>
            </div>
        </div>
    </section>
</div>

<section class="gaming_tournament-section">
    <div class="container">
        <div class="row" data-aos="fade-up">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pr-md-0 pr-sm-none">
                <div class="gaming_tournament_image">
                    <figure class="mb-0">
                        <img class="img-fluid" src="./assets/images/gaming_tournament_img.jpg" alt="">
                    </figure>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pl-md-0 pl-sm-none text-center">
                <div class="gaming_tournament_content">
                    <h2 class="mb-0">The BitBet Premier</h2>
                    <figure class="mb-0">
                        <img class="img-fluid" src="./assets/images/gaming_tournament_logo.png" alt="">
                    </figure>
                    <p class="sub_p mb-0 text-left">
                        BITBET is online betting and gambling 2.0,
                        reinventing the ways bets are placed online
                        using cryptocurrency, dedicated token
                        and latest blockchain technology.
                    </p>
                    <!--<p class="sub_p mb-4 d-lg-block d-none">
                        Duis aute irure dolor in reprehenderit in voluptate velit 
                        esse cillum dolore eu fugiat nulle ariatur.
                    </p>-->
                    <div class="btn_wrapper">
                        <a class="text-decoration-none readmore_btn" href="https://bitbet-organization.gitbook.io/bitbet-whitepaper/">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="footer-section">
    <div class="container">
        <div class="middle-portion">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="links mb-0 list-unstyled">
                        <li><a href="https://bitbetpremier.io/">Home</a></li>
                        <li><a href="https://bitbetpremier.io/about.html">About</a></li>
                        <li><a href="https://bitbetpremier.io/index.html"><figure class="mb-0"><img src="./assets/images/footer_logo.png" alt=""></figure></a></li>
                        <li><a href="https://bitbetpremier.io/tokenomics.html">Tokenomics</a></li>
                        <li><a href="https://bitbetpremier.io/team.html">Team</a></li>
                        <li class="icons"><a href="https://t.me/BitBetPremier"><i class="fa-brands fa-telegram" aria-hidden="true"></i></a></li>
                        <li class="icons"><a href="https://twitter.com/bitbetpremier"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a></li>
                        <li class="icons"><a href="https://medium.com/@bitbetpremier"><i class="fa-brands fa-medium" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
                <!--<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <ul class="mb-2 list-unstyled">
                        <li><a href="./index.html"><figure class="mb-0"><img src="./assets/images/footer_logo.png" alt=""></figure></a></li>
                    </ul>
                </div>-->
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <!--<ul class="mb-0 list-unstyled neg_margin">
                        <li class="icons"><a href="#"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a></li>
                        <li class="icons"><a href="https://twitter.com/bitbetpremier"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a></li>
                        <li class="icons"><a href="https://medium.com/@bitbetpremier"><i class="fa-brands fa-medium" aria-hidden="true"></i></a></li>
                    </ul>
                    <li>
                        <a href=""><i class="fa-brands fa-telegram" aria-hidden="true"></i></a>
                    </li>
                    <li>
                        <a href="https://twitter.com/bitbetpremier"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a>
                    </li>
                    <li>
                        <a href="https://medium.com/@bitbetpremier"><i class="fa-brands fa-medium" aria-hidden="true"></i></a>
                    </li>-->
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-portion">
        <div class="copyright col-xl-12"> 
            <p>Copyright 2024, BitBET Premier. All Rights Reserved.</p>
        </div>
    </div>
</div>
<!-- BLOG SECTION POPUP -->
<div id="blog-model-1" class="modal fade blog-model-con" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" ><i class="fa-solid fa-x"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="blog-box-item mb-0">
                    <div class="blog-img">
                        <figure class="mb-0">
                            <img src="./assets/images/blog_post_1.jpg" alt="blog-img" class="img-fluid">
                        </figure>
                    </div>
                    <div class="blog-content pl-0 pr-0">
                        <div class="blog-auteher-title">
                            <span>By Elina Parker</span>
                            <span class="float-lg-right">Sep 01, 2022</span>
                        </div>
                        <div class="span_wrapper">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i><span> 25 Oct, 2020  |  by admin</span>
                        </div>
                        <p class="blog_p mb-0">Magni dolores eos qui ratione voluptatem tempora incidunt sequi</p>
                        <p class="pp">
                            Quis nostrum exercitationem ullam corporis suscit labor iosam, nisi ut aliquid ex ea commodi conse aute irure dolor 
                            in reprehenderit in voluptateesse occaecat cuida at non proident, sunt in culpa qui officia deserun.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor eros a tellus auctor, nec suscipit nunc dignissim. Ut suscipit gravida augue sed elementum. Sed sed luctus nisl. Donec scelerisque nisi in sodales mattis. Vestibulum suscipit odio ac enim blandit sollicitudin. Aliquam ultrices sem quis urna placerat interdum. Etiam rutrum, quam sagittis tristique mollis, libero arcu scelerisque erat, eget tincidunt eros diam quis nunc.
                        </p>
                        <h2>Get In Touch With Us</h2>
                        <form class="contact-form blog-model-form">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">    
                                    <input type="text" class="form_style" placeholder="Name" name="name"> 
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">
                                    <input type="email" class="form_style" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">    
                                    <input type="tel" class="form_style" placeholder="Phone"> 
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">    
                                    <input type="text" class="form_style" placeholder="Subject"> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class=" form-group mb-0">    
                                    <textarea class="form_style" placeholder="Message" rows="3" name="comments"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="appointment-btn">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
       </div>
    </div>
</div>
<div id="blog-model-2" class="modal fade blog-model-con" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" ><i class="fa-solid fa-x"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="blog-box-item mb-0">
                    <div class="blog-img">
                        <figure class="mb-0">
                            <img src="./assets/images/blog_post_2.jpg" alt="blog-img" class="img-fluid">
                        </figure>
                    </div>
                    <div class="blog-content pl-0 pr-0">
                        <div class="blog-auteher-title">
                            <span>By Elina Parker</span>
                            <span class="float-lg-right">Sep 01, 2022</span>
                        </div>
                        <div class="span_wrapper">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i><span> 25 Oct, 2020  |  by admin</span>
                        </div>
                        <p class="blog_p mb-0">Totam rem aperiam, eaque ipsa quae ab illo inventore veritatis</p>
                        <p class="pp">
                            Quis nostrum exercitationem ullam corporis suscit labor iosam, nisi ut aliquid ex ea commodi conse aute irure dolor 
                            in reprehenderit in voluptateesse occaecat cuida at non proident, sunt in culpa qui officia deserun.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor eros a tellus auctor, nec suscipit nunc dignissim. Ut suscipit gravida augue sed elementum. Sed sed luctus nisl. Donec scelerisque nisi in sodales mattis. Vestibulum suscipit odio ac enim blandit sollicitudin. Aliquam ultrices sem quis urna placerat interdum. Etiam rutrum, quam sagittis tristique mollis, libero arcu scelerisque erat, eget tincidunt eros diam quis nunc.
                        </p>
                        <h2>Get In Touch With Us</h2>
                        <form class="contact-form blog-model-form">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">    
                                    <input type="text" class="form_style" placeholder="Name" name="name"> 
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">
                                    <input type="email" class="form_style" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">    
                                    <input type="tel" class="form_style" placeholder="Phone"> 
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">    
                                    <input type="text" class="form_style" placeholder="Subject"> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class=" form-group mb-0">    
                                    <textarea class="form_style" placeholder="Message" rows="3" name="comments"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="appointment-btn">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
       </div>
    </div>
</div>
<div id="blog-model-3" class="modal fade blog-model-con" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" ><i class="fa-solid fa-x"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="blog-box-item mb-0">
                    <div class="blog-img">
                        <figure class="mb-0">
                            <img src="./assets/images/blog_post_3.jpg" alt="blog-img" class="img-fluid">
                        </figure>
                    </div>
                    <div class="blog-content pl-0 pr-0">
                        <div class="blog-auteher-title">
                            <span>By Elina Parker</span>
                            <span class="float-lg-right">Sep 01, 2022</span>
                        </div>
                        <div class="span_wrapper">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i><span> 25 Oct, 2020  |  by admin</span>
                        </div>
                        <p class="blog_p mb-0">Duis aute irure dolor in reprehenderit in voluptate velit esse</p>
                        <p class="pp">
                            Quis nostrum exercitationem ullam corporis suscit labor iosam, nisi ut aliquid ex ea commodi conse aute irure dolor 
                            in reprehenderit in voluptateesse occaecat cuida at non proident, sunt in culpa qui officia deserun.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor eros a tellus auctor, nec suscipit nunc dignissim. Ut suscipit gravida augue sed elementum. Sed sed luctus nisl. Donec scelerisque nisi in sodales mattis. Vestibulum suscipit odio ac enim blandit sollicitudin. Aliquam ultrices sem quis urna placerat interdum. Etiam rutrum, quam sagittis tristique mollis, libero arcu scelerisque erat, eget tincidunt eros diam quis nunc.
                        </p>
                        <h2>Get In Touch With Us</h2>
                        <form class="contact-form blog-model-form">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">    
                                    <input type="text" class="form_style" placeholder="Name" name="name"> 
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">
                                    <input type="email" class="form_style" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">    
                                    <input type="tel" class="form_style" placeholder="Phone"> 
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-0">    
                                    <input type="text" class="form_style" placeholder="Subject"> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class=" form-group mb-0">    
                                    <textarea class="form_style" placeholder="Message" rows="3" name="comments"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="appointment-btn">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
       </div>
    </div>
</div>
<!-- Latest compiled JavaScript -->
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/custom.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="assets/js/owl.carousel.js"></script>
<script src="assets/js/carousel.js"></script>
<script src="assets/js/video-section.js"></script>
<script src="assets/js/animation.js"></script>
<script src="assets/js/counter.js"></script>
</body>
</html>