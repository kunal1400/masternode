# masternode
Plugin for Ondrej

# formula rank
1) (z*30)-9.99
2) z*7
3) z*30
4) z*365
5) daily_income_usd365100/((price_usdrequired_coins_for_masternode)+(9.9912))
6) daily_income_usd365100/((price_usdrequired_coins_for_masternode)+(29.9912))
7) (z*30)-29.99;
8) y * pivx daily_income_usd * 30 * 0.01;

# shortcodes
1) [mno coin_ticker="PIVX" get="daily_income_usd" formula=1]
2) [mno coin_ticker="PIVX" get="daily_income_usd" formula=2 class="123" do_round=0]
3) [mno coin_ticker="PIVX" get="daily_income_usd" formula=3 class="123" do_round=0]
4) [mno coin_ticker="PIVX" get="daily_income_usd" formula=4 class="123" do_round=0]
5) [mno coin_ticker="PIVX" get="daily_income_usd" formula=5 class="123" do_round=0]
6) [mno coin_ticker="PIVX" get="daily_income_usd" formula=6 class="123" do_round=0]
7) [mno coin_ticker="PIVX" get="daily_income_usd" formula=7 class="123" do_round=0]
8) [mno coin_ticker="PIVX" get="daily_income_usd" formula=8 class="123" do_round=0]

# shorcodes for formula8
1) [formula8_input class="123"]
2) [formula8_output class="456"]