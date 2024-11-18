<?php include $settings['pluginDirectory'] . "/fpp-sumup/pluginUpdate.php" ?>
<h2>SumUp Status</h2>

<?php
include_once 'sumup.common.php';
$pluginName = 'sumup';

$pluginJson = convertAndGetSettingsSumUp($pluginName);
$plugindatabase = convertAndGetSettingsSumUp($pluginName . "-transactions");

$setupUrl = 'plugin.php?' . http_build_query([
  '_menu' => 'content',
  'plugin' => 'fpp-' . $pluginName,
  'page' => 'setup.php'
]);

function sortByTimestampDesc($a, $b)
{
  return $b['timestamp'] > $a['timestamp'];
}

function getTransactions()
{
  $data = convertAndGetSettingsSumUp('sumup-transactions');
  usort($data, 'sortByTimestampDesc');
  return $data;
}

function getTransactionsTotal()
{
  $transactions = convertAndGetSettingsSumUp('sumup-transactions');
  $total = 0;
  foreach ($transactions as $transaction) {
    $total += $transaction['amount'];
  }
  return number_format($total, 2);
}
?>

<head>
  <link rel="stylesheet" href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css">
  <link rel="stylesheet" href="/plugin.php?plugin=fpp-zettle&file=zettle.css&nopage=1">
  <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
  <script type="text/javascript" src="/plugin.php?plugin=fpp-zettle&file=zettle.js&nopage=1"></script>
  <style>
    .avatar {
      width: 40px;
      min-width: 40px;
      height: 40px
    }

    .avatar.no-thumbnail {
      background-color: var(--color-300);
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .rounded {
      border-radius: .25rem !important
    }

    .ms-3 {
      margin-left: 1rem !important
    }
  </style>
</head>

<body>
  <h3>Transactions (Total: <?php echo getTransactionsTotal(); ?>)</h3>
  <div id="transactions"></div>
  <br>
  <input id="clear_transactions" class="buttons" value="Clear Transactions">
  <script>
    const grid = new gridjs.Grid({
      columns: [{
        id: 'timestamp',
        name: 'Time',
        width: '50%',
        formatter: (data) => {
          return new Date(data).toGMTString();
        }
      }, {
        id: 'amount',
        name: 'Amount £',
        width: '50%',
        formatter: (cell) => `£${cell}`
      }],
      sort: true,
      resizable: true,
      style: {
        table: {
          border: '3px solid #ccc'
        },
        th: {
          'background-color': 'rgba(0, 0, 0, 0.1)',
          color: '#000',
          'border-bottom': '3px solid #ccc',
          'text-align': 'center'
        },
        td: {
          'text-align': 'center'
        }
      },
      server: {
        url: '/api/configfile/plugin.fpp-sumup-transactions.json',
        then: data => data.map(card => [card.timestamp, card.amount])
      }
    });
    grid.render(document.getElementById("transactions"));

    setInterval(function() {
      grid.updateConfig({
        server: {
          url: '/api/configfile/plugin.fpp-sumup-transactions.json',
          then: data => data.map(card => [card.timestamp, card.amount])
        }
      }).forceRender();
    }, 30000);
    $(function() {
      function ajaxGet(url, feild) {
        $.ajax({
          type: "GET",
          url: url,
          success: function(data) {
            $('span#' + feild).html(data);
          }
        });
      }
    });
  </script>
</body>
