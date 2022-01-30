# 世界人口轉盤（命令行文字版）

發想自 [z358z358](https://github.com/z358z358) 的[自訂轉盤](https://z358z358.github.io/Roulette/)，用 PHP 寫了一個簡單的無介面命令行版本。  
2022 年 1 月 26 日始動，原本放在 Hodgepodge 專案的 `Funny/Roulette` 資料夾內，  
2022 年 1 月 29 日改成獨立專案。


## 說明

### 執行起點

* `cli/Roulette.php`  
  命令行執行，可用 3 個參數（均非必填）：
  - `--round=n`：`n` 為循環次數；不帶的話，預設為 1，即只跑 1 次
  - `--output`：是否直接輸出於命令行，不帶的話，預設為不輸出
  - `--no-tsv`：是否輸出 TSV 紀錄檔，不帶的話，預設會輸出到 `storage/tsv/record.tsv`

### 資料庫

* `storage/tsv/src.tsv`：原始 TSV 檔
* `storage/sqlite/Pop.db`：SQLite 資料庫
* `database/migrations` 資料夾下有 SQL 格式的資料遷移檔，由 `cli/Migrations` 中的同名腳本執行操作

### 資料操作

* 執行 `cli/Data/Build.php` 可將原始 TSV 資料灌到 SQLite 資料庫，  
  同時生成世界各一級行政區人口數獨立計算（`WorldPopulation` 資料表）及累計的人口資料（`WorldPopulationAccumulation` 資料表）
* `cli/Data/Build.php` 係先後執行  
  `cli/Data/Truncate.php`（清除現有資料庫）及 `cli/Data/Insert.php`（從原始 TSV 檔重建資料）2 個腳本，  
  它們可以各自獨立執行，但須注意先後順序
* `cli/Data/Truncate.php` 和 `cli/Data/Insert.php` 又是各自先後執行  
  `cli/Data/Source` 及 `cli/Data/Accumulated` 2 個資料夾下的同名腳本，  
  它們分別呼叫 `app/Handlers/SourceHandler.php` 和 `app/Handlers/AccumulationHandler.php` 中的方法來操作資料

### 日誌

* `storage/logs` 資料夾內，依 `log_(YYYYMMDD).log` 的格式命名
