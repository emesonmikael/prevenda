// Configuração da conexão e endereço do contrato
const contractAddress = "0x881f67A4D717e2818Fc16447fD736EbC4d7f8892"; // Substitua pelo endereço do contrato
const contractABI = [[
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "account",
				"type": "address"
			}
		],
		"name": "balanceOf",
		"outputs": [
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "recipient",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "amount",
				"type": "uint256"
			}
		],
		"name": "transfer",
		"outputs": [
			{
				"internalType": "bool",
				"name": "",
				"type": "bool"
			}
		],
		"stateMutability": "nonpayable",
		"type": "function"
	}
] /* ABI DO CONTRATO AQUI */ ];

// Configuração da conexão e endereço do contrato


let web3;
let presaleContract;

async function connectWallet() {
  if (window.ethereum) {
    try {
      // Solicitar conexão com a carteira
      const accounts = await ethereum.request({ method: "eth_requestAccounts" });
      const walletAddress = accounts[0];

      // Atualizar a interface com o endereço da carteira conectada
      document.getElementById("walletAddress").innerText = `Carteira conectada: ${shortenAddress(walletAddress)}`;
      document.getElementById("connectWalletButton").innerText = "Conectado";
      document.getElementById("connectWalletButton").disabled = true;

      // Inicializar Web3 e o contrato
      web3 = new Web3(window.ethereum);
      presaleContract = new web3.eth.Contract(contractABI, contractAddress);
      console.log("Conectado à blockchain com a carteira:", walletAddress);
    } catch (error) {
      console.error("Erro ao conectar à carteira:", error);
      alert("Erro ao conectar à carteira. Por favor, tente novamente.");
    }
  } else {
    alert("MetaMask não encontrada. Por favor, instale a extensão MetaMask.");
  }
}

window.addEventListener('load', async () => {
  if (typeof window.ethereum !== 'undefined') {
    console.log("MetaMask detectada.");
  } else {
    alert("MetaMask não está instalada. Por favor, instale a extensão para usar esta funcionalidade.");
  }
});

// Função utilitária para encurtar o endereço
function shortenAddress(address) {
  return `${address.substring(0, 6)}...${address.substring(address.length - 4)}`;
}

// Outras funções (buyTokens, getBNBBalance, withdrawTokens, getTokensSold) permanecem as mesmas.