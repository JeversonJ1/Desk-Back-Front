import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

let vendasChart = null;
let estoqueChart = null;
let atualizandoDashboard = false;
let ultimaAtualizacaoDashboard = 0;
const INTERVALO_MINIMO_ATUALIZACAO_MS = 5000;

function getSessionId() {
  return typeof AuthHelper !== 'undefined' ? AuthHelper.getSessionId() : localStorage.getItem('sessionId');
}

// ================================
// ATUALIZAR DASHBOARD
// ================================
async function atualizarDashboard() {
  try {
    const agora = Date.now();
    if (document.hidden) return;
    if (atualizandoDashboard || (agora - ultimaAtualizacaoDashboard) < INTERVALO_MINIMO_ATUALIZACAO_MS) {
      return;
    }

    atualizandoDashboard = true;
    console.log('=== Atualizando Dashboard ===');

    // Carregar dados
    const sessionId = getSessionId();
    const dados = await window.api.obterDashboard(sessionId);
    const produtos = await window.api.listarProdutos(sessionId);
    const pedidos = await window.api.listarPedidos(sessionId);

    // Atualizar cards principais
    const elemProdutos = document.getElementById('totalProdutos');
    const elemEstoque = document.getElementById('totalEstoque');
    const elemPedidos = document.getElementById('totalPedidos');
    const elemValor = document.getElementById('valorVendas');

    if (elemProdutos) elemProdutos.textContent = dados.totalProdutos || 0;
    if (elemEstoque) elemEstoque.textContent = dados.estoqueTotal || 0;
    if (elemPedidos) elemPedidos.textContent = dados.totalPedidos || 0;
    if (elemValor) {
      elemValor.textContent = 'R$ ' + (dados.valorVendas || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    }

    // Atualizar alertas de estoque baixo
    atualizarAlertas(produtos);

    // Atualizar top produtos
    atualizarTopProdutos(produtos);

    // Atualizar gráficos
    atualizarGraficos(produtos, pedidos);

    // Atualizar timestamp
    atualizarTimestamp();

    console.log('✓ Dashboard atualizado com sucesso');
  } catch (erro) {
    console.error('Erro ao atualizar dashboard:', erro);
  } finally {
    atualizandoDashboard = false;
    ultimaAtualizacaoDashboard = Date.now();
  }
}

// ================================
// ATUALIZAR ALERTAS DE ESTOQUE BAIXO
// ================================
function atualizarAlertas(produtos) {
  const container = document.getElementById('alertasEstoque');
  if (!container) return;

  // Produtos com estoque < 20
  const alertos = produtos.filter(p => p.estoque < 20).sort((a, b) => a.estoque - b.estoque);

  if (alertos.length === 0) {
    container.innerHTML = '<div style="padding: 20px; text-align: center;"><p class="text-muted" style="margin: 0;">✓ Nenhum alerta de estoque</p></div>';
    return;
  }

  container.innerHTML = alertos.slice(0, 6).map(p => {
    const percentual = Math.round((p.estoque / 100) * 100); // Assumir 100 como máximo
    let cor = '#51cf66'; // Verde
    let bg = 'rgba(81, 207, 102, 0.1)';

    if (p.estoque < 5) {
      cor = '#ff6b6b'; // Vermelho crítico
      bg = 'rgba(255, 107, 107, 0.1)';
    } else if (p.estoque < 15) {
      cor = '#ffa94d'; // Laranja atenção
      bg = 'rgba(255, 169, 77, 0.1)';
    }

    return `
      <div style="padding: 14px; border-bottom: 1px solid #333; background: ${bg}; border-left: 4px solid ${cor};">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
          <div style="font-weight: bold; color: #fff; font-size: 14px;">${p.nome.substring(0, 30)}</div>
          <span style="color: ${cor}; font-weight: bold; font-size: 13px;">${p.estoque} un</span>
        </div>
        <div style="width: 100%; height: 6px; background: #333; border-radius: 3px; overflow: hidden;">
          <div style="width: ${percentual}%; height: 100%; background: ${cor}; transition: width 0.3s;"></div>
        </div>
      </div>
    `;
  }).join('');
}

// ================================
// ATUALIZAR TOP PRODUTOS
// ================================
function atualizarTopProdutos(produtos) {
  const container = document.getElementById('topEstoque');
  if (!container) return;

  // Top 5 produtos por estoque
  const top = [...produtos].sort((a, b) => b.estoque - a.estoque).slice(0, 5);

  container.innerHTML = top.map((p, i) => {
    const valorProduto = (p.preco * p.estoque).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    const percentualMax = Math.min((p.estoque / 500) * 100, 100);

    return `
      <div style="padding: 16px; border-bottom: 1px solid #333; cursor: pointer;" onclick="window.location.href='produtos.html'" onmouseover="this.style.background='rgba(255,216,77,0.05)'" onmouseout="this.style.background='transparent'" style="transition: all 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
          <div>
            <div style="color: #F2C84B; font-size: 13px; font-weight: bold; margin-bottom: 4px;">#${i + 1}</div>
            <div style="color: #e0e0e0; font-weight: 600; font-size: 14px;">${p.nome.substring(0, 28)}</div>
          </div>
          <div style="text-align: right;">
            <div style="color: #F2C84B; font-weight: bold; font-size: 14px;">${p.estoque} un</div>
            <div style="color: #888; font-size: 12px;">R$ ${valorProduto}</div>
          </div>
        </div>
        <div style="width: 100%; height: 6px; background: #333; border-radius: 3px; overflow: hidden;">
          <div style="width: ${percentualMax}%; height: 100%; background: linear-gradient(90deg, #F2C84B, #F2C84B); transition: width 0.3s;"></div>
        </div>
      </div>
    `;
  }).join('');
}

// ================================
// ATUALIZAR GRÁFICOS
// ================================
function atualizarGraficos(produtos, pedidos) {
  atualizarGraficoVendas(pedidos);
  atualizarGraficoEstoque(produtos);
}

// ================================
// FILTRO DE VENDAS
// ================================
let filtroVendasAtual = 'mes'; // Opções: 'dia', 'mes', 'ano'
let pedidosCache = [];

window.mudarFiltroVendas = function(filtro) {
  filtroVendasAtual = filtro;
  
  // Atualizar botões visuais
  document.querySelectorAll('.btn-filtro-vendas').forEach(btn => btn.classList.remove('active'));
  const activeBtn = document.getElementById('btn-filtro-' + filtro);
  if (activeBtn) activeBtn.classList.add('active');
  
  if (pedidosCache.length > 0) {
    atualizarGraficoVendas(pedidosCache);
  }
};

// ================================
// GRÁFICO DE VENDAS
// ================================
function atualizarGraficoVendas(pedidos) {
  if (pedidos) pedidosCache = pedidos;
  const canvas = document.getElementById('vendasChart');
  if (!canvas || !pedidosCache) return;

  const validPedidos = pedidosCache.filter(p => p.status !== 'cancelado');
  
  let labels = [];
  let valores = [];
  
  const hoje = new Date();

  if (filtroVendasAtual === 'dia') {
    // Últimos 7 dias
    const dadosPorDia = {};
    for (let i = 6; i >= 0; i--) {
      const d = new Date(hoje);
      d.setDate(d.getDate() - i);
      const dataStr = d.toISOString().split('T')[0]; // YYYY-MM-DD
      const label = d.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
      labels.push(label);
      dadosPorDia[dataStr] = 0;
    }

    validPedidos.forEach(p => {
      const pDate = new Date(p.data_pedido || p.criado_em || p.data);
      const dStr = pDate.toISOString().split('T')[0];
      if (dadosPorDia[dStr] !== undefined) {
        dadosPorDia[dStr] += Number(p.total_pedido || p.total) || 0;
      }
    });
    valores = Object.values(dadosPorDia);

  } else if (filtroVendasAtual === 'mes') {
    // Últimos 6 meses
    const dadosPorMes = {};
    for (let i = 5; i >= 0; i--) {
      const d = new Date(hoje.getFullYear(), hoje.getMonth() - i, 1);
      const chave = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2, '0')}`;
      const label = d.toLocaleDateString('pt-BR', { month: 'short', year: '2-digit' }).replace('.', '');
      labels.push(label);
      dadosPorMes[chave] = 0;
    }

    validPedidos.forEach(p => {
      const pDate = new Date(p.data_pedido || p.criado_em || p.data);
      const chave = `${pDate.getFullYear()}-${String(pDate.getMonth()+1).padStart(2, '0')}`;
      if (dadosPorMes[chave] !== undefined) {
        dadosPorMes[chave] += Number(p.total_pedido || p.total) || 0;
      }
    });
    valores = Object.values(dadosPorMes);

  } else if (filtroVendasAtual === 'ano') {
    // Últimos 5 anos
    const dadosPorAno = {};
    for (let i = 4; i >= 0; i--) {
      const ano = hoje.getFullYear() - i;
      labels.push(ano.toString());
      dadosPorAno[ano] = 0;
    }

    validPedidos.forEach(p => {
      const pDate = new Date(p.data_pedido || p.criado_em || p.data);
      const ano = pDate.getFullYear();
      if (dadosPorAno[ano] !== undefined) {
        dadosPorAno[ano] += Number(p.total_pedido || p.total) || 0;
      }
    });
    valores = Object.values(dadosPorAno);
  }

  if (vendasChart) {
    vendasChart.data.labels = labels;
    vendasChart.data.datasets[0].data = valores;
    vendasChart.update();
    return;
  }

  const ctx = canvas.getContext('2d');
  vendasChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Vendas (R$)',
        data: valores,
        borderColor: '#ffc107',
        backgroundColor: 'rgba(255, 193, 7, 0.1)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: '#ffc107',
        pointBorderColor: '#fff',
        pointRadius: 5
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          labels: { color: '#fff' }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              let label = context.dataset.label || '';
              if (label) {
                label += ': ';
              }
              if (context.parsed.y !== null) {
                label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed.y);
              }
              return label;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: '#444' },
          ticks: { 
            color: '#fff',
            callback: function(value) {
                return 'R$ ' + value;
            }
          }
        },
        x: {
          grid: { color: '#444' },
          ticks: { color: '#fff' }
        }
      }
    }
  });
}

// ================================
// GRÁFICO DE ESTOQUE
// ================================
function atualizarGraficoEstoque(produtos) {
  const canvas = document.getElementById('estoqueChart');
  if (!canvas) return;

  // Top 5 produtos por estoque
  const top = [...produtos].sort((a, b) => b.estoque - a.estoque).slice(0, 5);

  const labels = top.map(p => p.nome.substring(0, 15));
  const valores = top.map(p => p.estoque);

  if (estoqueChart) {
    estoqueChart.data.labels = labels;
    estoqueChart.data.datasets[0].data = valores;
    estoqueChart.update();
    return;
  }

  const ctx = canvas.getContext('2d');
  estoqueChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: valores,
        backgroundColor: [
          '#ffc107',
          '#17a2b8',
          '#28a745',
          '#dc3545',
          '#6f42c1'
        ],
        borderColor: '#1a1a1a',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'bottom',
          labels: {
            color: '#fff',
            padding: 15,
            font: { size: 12 }
          }
        }
      }
    }
  });
}

// ================================
// ATUALIZAR TIMESTAMP
// ================================
function atualizarTimestamp() {
  const elem = document.getElementById('ultimaAtualizacao');
  if (!elem) return;

  const agora = new Date();
  const horas = agora.getHours().toString().padStart(2, '0');
  const minutos = agora.getMinutes().toString().padStart(2, '0');
  const dia = agora.toLocaleDateString('pt-BR');

  elem.textContent = `Última atualização: ${dia} às ${horas}:${minutos}`;
}

// ================================
// EVENT LISTENERS
// ================================
document.addEventListener('DOMContentLoaded', () => {
  console.log('Dashboard iniciando...');

  // Botão atualizar
  const btnAtualizar = document.getElementById('btnAtualizar');
  if (btnAtualizar) {
    btnAtualizar.addEventListener('click', () => {
      btnAtualizar.textContent = 'Atualizando...';
      btnAtualizar.disabled = true;
      atualizarDashboard().then(() => {
        btnAtualizar.textContent = 'Atualizar';
        btnAtualizar.disabled = false;
      });
    });
  }

  // Atualizar ao carregar
  atualizarDashboard();

  // Recarregar a cada 15 segundos (sincronização automática visível rápida)
  setInterval(() => {
    if (!document.hidden) {
      atualizarDashboard();
    }
  }, 15000);
});


