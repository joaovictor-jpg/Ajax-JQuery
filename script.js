$(document).ready(() => {
	$('#documentacao').on('click', () => {
        // $('#pagina').load('documentacao.html')
        // $.get('documentacao.html', (data) => {
        //     $('#pagina').html(data)
        // })
        $.post('documentacao.html', (data) => {
            $('#pagina').html(data)
        })
    })

    $('#suporte').on('click', () => {
        // $('#pagina').load('suporte.html')
        // $.get('suporte.html', (data) => {
        //     $('#pagina').html(data)
        // })
        $.post('suporte.html', (data) => {
            $('#pagina').html(data)
        })
    })

    //ajax
    $('#competecia').on('change', (e) =>{

        let competencia = $(e.target).val();

        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`,
            dataType: 'json',
            success: (dados) =>{
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
                // console.log(dados.numeroVendas, dados.totalVendas)
            },
            error: (erro) =>{console.log(erro)}
        })

        $.ajax({
            type: 'GET',
            url: 'cliente.php',
            dataType: 'json',
            success: (dados) =>{
                $('#ativo').html(dados.clienteAtivo)
                $('#inativo').html(dados.ClienteInativo)
                // console.log(dados.clienteAtivo, dados.ClienteInativo)
            },
            error: (erro) =>{console.log(erro)}
        })

        $.ajax({
            type: 'GET',
            url: 'comentarios.php',
            dataType: 'json',
            success: (dados) =>{
                $('#reclamacao').html(dados.reclamacao)
                $('#elogios').html(dados.elogios)
                $('#sugestoes').html(dados.sugestoes_melhorias)
                // console.log(dados.reclamacao, dados.elogios, dados.sugestoes_melhorias)
            },
            error: (erro) =>{console.log(erro)}
        })

        $.ajax({
            type: 'GET',
            url: 'despesas.php',
            data: `competencia=${competencia}`,
            dataType: 'json',
            success: (dados) =>{
                $('#despesas').html(dados.despesa)
                // console.log(dados.despesa)
            },
            error: (erro) =>{console.log(erro)}
        })
        
    })
})
