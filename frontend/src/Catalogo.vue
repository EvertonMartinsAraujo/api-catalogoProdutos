<template>
  <div class="produtos-wrapper">
        <!-- Cabeçalho com usuário e pesquisa -->
    <header class="cabecalho">
      <div class="nome-usuario">
        Olá, {{ user.name }}<br>
      </div>
      <div class="campo-pesquisa">
      <input v-model="termoBusca" type="text" placeholder="Pesquisar produtos..."/>
      </div>
    </header>
    <div class="produto-container" v-for="produto in produtosFiltrados" :key="produto.id"><br>
      <div>
        <img :src="`http://localhost:8000/${produto.imagem}`" v-if="produto.imagem" class="produto-imagem"/>
      </div>
      <div style="text-align: center;">
        {{ produto.nome }}<br>
        {{ produto.descricao }}<br>
        R$ {{ produto.preco }}  
        <button @click="comprar(produto)">Comprar</button>
        <!--para adm -->
        <div v-if="user && user.role === 'admin'">
          <button @click="editar(produto)">Editar</button>
          <button @click="deletar(produto.id)">Excluir</button>
        </div>
        
        <!-- tela para edição-->
        <div v-if="prodEditando" class="modal-overlay">
          <div class="modal-content">
            <h2>Editar Produto</h2>
              <label>Nome:</label>
              <input v-model="prodEditando.nome"><br>
              <label>Descrição:</label>
              <input v-model="prodEditando.descricao"><br>
              <label>Preço:</label>
              <input type="number" v-model="prodEditando.preco"><br>
              <label>Imagem:</label>
              <input type="file" @change="handleImagemChange"><br><!--edição imagem-->
              <button @click="salvarEdicao">Salvar</button>
              <button @click="cancelarEdicao">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      produtos: [],
      prodEditando:null,
      imagemSelecionada: null,
      user: {},
      termoBusca: '',
    }
  },

  computed: {
    produtosFiltrados() {
      const termo = this.termoBusca.toLowerCase();

      return this.produtos.filter(produto => {
        return (
          produto.nome.toLowerCase().includes(termo) ||
          produto.descricao.toLowerCase().includes(termo) ||
          produto.preco.toString().includes(termo)
        );
      });
    }
  },

  async mounted() {
    const token = localStorage.getItem('token')
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    const prodRes = await axios.get('http://localhost:8000/api/produto')
    this.produtos = prodRes.data
    const userRes = await axios.post('http://localhost:8000/api/auth/me')
    this.user = userRes.data
    console.log("Usuário logado:", this.user)
  },

  methods: {
    async adicionar(id) {
      await axios.put(`http://localhost:8000/api/produto/${id}`)
      this.produtos = this.produtos.filter( p => p.id)
    },

    async deletar(id) {
      await axios.delete(`http://localhost:8000/api/produto/${id}`)
      this.produtos = this.produtos.filter(p => p.id !== id)
    },

    editar(produtos) {
      this.prodEditando = {
        id: produtos.id,
        nome: produtos.nome,
        descricao: produtos.descricao,
        preco: produtos.preco,
    }},

      handleImagemChange(event) {
      this.imagemSelecionada = event.target.files[0]
      },

    async salvarEdicao(){

      try{
        const editado = new FormData();
        editado.append('id', this.prodEditando.id);
        editado.append('nome', this.prodEditando.nome);
        editado.append('descricao', this.prodEditando.descricao);
        editado.append('preco', this.prodEditando.preco);
        editado.append('_method', 'PUT');
        
        if(this.imagemSelecionada){
          editado.append('imagem',this.imagemSelecionada);
        }

        await axios.post(`http://localhost:8000/api/produto/${this.prodEditando.id}`, editado, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        //Retorno ok
        alert('Cadastro Atualizado')
        this.prodEditando = null
        this.imagemSelecionada = null
        this.recarregarProdutos();

      }catch (erro){//deu ruim
        console.error("Erro ao atualizar cadastro:", erro)
        alert('Erro ao atualizar cadastro')
        this.prodEditando = null;
      }
    },
    async cancelarEdicao(){
      this.prodEditando=null
    },
    
    async recarregarProdutos() {
      const prodResp = await axios.get('http://localhost:8000/api/produto')
      this.produtos = prodResp.data
    },
    comprar(produto) {
      alert(`Produto selecionado: ${produto.nome}`);
    }

  }
  
}
</script>


<style>

body {
  background-color: #000;
  color: #fff;
  margin: 0;
  font-family: Arial, sans-serif;
}

.cabecalho {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #007bff;
  color: white;
  padding: 10px 20px;
}

.nome-usuario {
  font-weight: bold;
}

.produto-container {
  background-color: #1a1a1a;
  color: white;
  padding: 15px;
  border-radius: 8px;
  width: 200px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.produto-imagem {
  width: 150px;
  height: 150px;
  object-fit: cover;
  margin-bottom: 10px;
  border: 1px solid #444;
}

.produtos-wrapper {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  gap: 20px;
  padding: 20px;
  background-color: #000; /* fundo preto */
  color: #fff;
}

button {
  background-color: #fff;
  color: #000;
  border: none;
  padding: 6px 12px;
  margin: 5px;
  cursor: pointer;
  border-radius: 4px;
}


button:hover {
  background-color: #ccc;
}

.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex; justify-content: center; align-items: center;
  z-index: 1000;
}

.modal-content {
  color: black;
  background: white;
  padding: 40px;
  border-radius: 8px;
  min-width: 300px;
}

</style>