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

#shortcodes
[mno coin_ticker="PIVX" get="daily_income_usd" formula=1]
[mno coin_ticker="PIVX" get="daily_income_usd" formula=2]
[mno coin_ticker="PIVX" get="daily_income_usd" formula=3]
[mno coin_ticker="PIVX" get="daily_income_usd" formula=4]
[mno coin_ticker="PIVX" get="daily_income_usd" formula=5]
[mno coin_ticker="PIVX" get="daily_income_usd" formula=6]
[mno coin_ticker="PIVX" get="daily_income_usd" formula=7]
[mno coin_ticker="PIVX" get="daily_income_usd" formula=8]

#shorcodes for formula8
[formula8_input class="123"]
[formula8_output class="456"]