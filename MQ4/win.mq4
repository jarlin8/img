//+------------------------------------------------------------------+
//|                                                      ProjectName |
//|                                      Copyright 2018, CompanyName |
//|                                       http://www.companyname.net |
//+------------------------------------------------------------------+
#property link          "#"
#property version       "1.01"
#property strict
#property copyright     "smartlimit - 2023"
#property description   "smartlimit定制版."

extern int Distance = 4000; // 限价距离（点）
extern double LotSize = 0.01; // 交易手数
extern double LotSizeAdd = 0.03; // 间隔递增(手)
extern double MaxLotSize = 0.07; // 间隔递增与倍数递增分界（手）
extern double LotSizeTimes = 1.5; // 倍数递增
extern double TargetProfit = 1.00; // 盈利目标（$）
const int DELAY_MS = 100; // 延迟的毫秒数
extern int MagicNumber = 12345; // 魔术码

// 全局变量
datetime OrderOpenTime;
double CurrentBuyLotSize;
double CurrentSellLotSize;
double BuyLimitLots = 0;
double SellLimitLots = 0;
// double TotalProfit = 0;

//+------------------------------------------------------------------+
//| 初始化功能                                                        |
//+------------------------------------------------------------------+
int OnInit()
  {
// 初始化全局变量
   OrderOpenTime = TimeCurrent();
   CurrentBuyLotSize = LotSize;
   CurrentSellLotSize = LotSize;
   BuyLimitLots = 0;
   SellLimitLots = 0;
   //TotalProfit = 0;

   return(INIT_SUCCEEDED);
  }

//+------------------------------------------------------------------+
//| 定义关闭所有仓单参数                                               |
//+------------------------------------------------------------------+
void CloseAllOrders()
  {
   int totalOrders = OrdersTotal();
   for(int i = totalOrders - 1; i >= 0; i--)
     {
      if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
        {
         if(OrderMagicNumber() == MagicNumber && OrderCloseTime() == 0)
           {
            if(OrderType() == OP_BUY || OrderType() == OP_SELL)
              {
               if(OrderClose(OrderTicket(), OrderLots(), Bid, 3, Red))
                 {
                  Print("订单 #", OrderTicket(), " 被成功关闭");
                 }
               else
                 {
                  Print("订单 #", OrderTicket(), " 关闭失败并报错 #", GetLastError());
                 }
              }
            else
               if(OrderType() == OP_BUYSTOP || OrderType() == OP_SELLSTOP || OrderType() == OP_BUYLIMIT || OrderType() == OP_SELLLIMIT)
                 {
                  if(OrderDelete(OrderTicket()))
                    {
                     Print("订单 #", OrderTicket(), " 被成功删除");
                    }
                  else
                    {
                     Print("订单 #", OrderTicket(), " 删除失败并报错 #", GetLastError());
                    }
                 }
           }
         else
           {
            Print("订单 #", OrderTicket(), " 已经关闭或删除");
            //TotalProfit = 0;
            CurrentBuyLotSize = LotSize;
            CurrentSellLotSize = LotSize;
            BuyLimitLots = 0;
            SellLimitLots = 0;
           }
        }
      else
        {
         Print("无法选择订单 #", i, "，错误码 #", GetLastError());
        }
     }
  }

//+------------------------------------------------------------------+
//| 参数变动关闭所有仓单的初始化功能                                    |
//+------------------------------------------------------------------+
void OnDeinit(const int reason)
  {
// 关闭所有仓单
   CloseAllOrders();
  }

//+------------------------------------------------------------------+
//|  主逻辑功能                                                       |
//+------------------------------------------------------------------+
void OnTick()
  {

   /* // 获取最大手数的已成交买单

      for(int i = 0; i < OrdersTotal(); i++)
        {
         if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
           {
            if(OrderType() == OP_BUY)
              {
               double lots = OrderLots();
               if(lots > BuyLimitLots)
                  BuyLimitLots = lots;
              }
            if(OrderMagicNumber() == MagicNumber && OrderCloseTime() != 0)
               { // 有订单被关闭则重置手数和利润
                 TotalProfit = 0;
                 CurrentBuyLotSize = LotSize;
                 CurrentSellLotSize = LotSize;
               }
           }
        }

   // 获取最大手数的已成交卖单
   // MODE_TRADES 默认从交易池中选择的订单（打开和挂单）

      for(int i = 0; i < OrdersTotal(); i++)
        {
         if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
           {
            if(OrderType() == OP_SELL)
              {
               double lots = OrderLots();
               if(lots > SellLimitLots)
                  SellLimitLots = lots;
              }
           }
        }*/
// 获取最大手数的已成交买单和卖单
   for(int i = 0; i < OrdersTotal(); i++)
     {
      if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
        {
         if(OrderType() == OP_BUY)
           {
            double lots = OrderLots();
            if(lots > BuyLimitLots)
              {
               BuyLimitLots = lots;
              }
           }
         else
            if(OrderType() == OP_SELL)
              {
               double lots = OrderLots();
               if(lots > SellLimitLots)
                 {
                  SellLimitLots = lots;
                 }
              }
         if(OrderMagicNumber() == MagicNumber && OrderCloseTime() != 0)
           {
            // 有订单被关闭则重置手数和利润
            //TotalProfit = 0;
            CurrentBuyLotSize = LotSize;
            CurrentSellLotSize = LotSize;
           }
        }
     }

// 计算BuyLimit 和 SellLimit 的价格
   double BuyLimitPrice = Ask - Distance * Point;
   double SellLimitPrice = Bid + Distance * Point;

// 计算持仓总利润
// AccountProfit() = AccountInfoDouble(ACCOUNT_PROFIT) = AccountEquity() - AccountBalance() 不同计算方法 不用遍历所有订单可能导致算上其他EA和手动单
// OrderProfit() + OrderSwap() + OrderCommission() 去掉了库存费和手续费
   /*   double Commission = 0;
      double Profit = 0;
      for(int i = OrdersTotal() - 1; i >= 0; i--)
        {
         if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
           {
            if(OrderMagicNumber() == MagicNumber && OrderCloseTime() == 0 && (OrderType() == OP_BUY || OrderType() == OP_SELL))
              {
               Profit += OrderProfit();
               Commission += OrderCommission();
               TotalProfit = Profit + Commission;
              }
           }
        }
   */
// 计算持仓总利润  不用遍历所有订单
   double Equity = AccountInfoDouble(ACCOUNT_EQUITY);
   double Balance = AccountInfoDouble(ACCOUNT_BALANCE);
   double TotalProfit = Equity - Balance;

// 检查是否已经达到目标利润
// 如果达成目标利润 平仓 继续挂单
   if(TotalProfit >= TargetProfit)
     {
      CloseAllOrders();
      //TotalProfit = 0;
      CurrentBuyLotSize = LotSize;
      CurrentSellLotSize = LotSize;
      BuyLimitLots = 0;
      SellLimitLots = 0;
      // 平仓后等待延迟 继续挂单
      // 获取当前时间
      datetime currentTime = TimeCurrent();

      // 判断距离上一次交易信号的时间是否超过设定的延迟时间
      if(currentTime - OrderOpenTime < DELAY_MS)
        {
         return;
        }

      // 如距上次交易时间已超延迟时间，则执行交易操作
      int BuyTicket = OrderSend(Symbol(), OP_BUYLIMIT, CurrentBuyLotSize, BuyLimitPrice, 3, 0, 0, "BuyLimit", MagicNumber, 0, clrGreen);
      int SellTicket = OrderSend(Symbol(), OP_SELLLIMIT, CurrentSellLotSize, SellLimitPrice, 3, 0, 0, "SellLimit", MagicNumber, 0, clrRed);
      if(BuyTicket > 0 || SellTicket > 0)
        {
         // 交易成功，记录当前时间
         OrderOpenTime = currentTime;
        }
      else
        {
         // 交易失败，打印错误信息
         Print("下单失败: , error code = ", GetLastError());
        }
     }

// 如果未达成目标利润 继续挂单
   else
      if(TotalProfit < TargetProfit)
        {

         // 计算买单的手数
         if(BuyLimitLots == 0)
           {
            CurrentBuyLotSize = LotSize;
           }
         else
            if(BuyLimitLots <= MaxLotSize)
              {
               CurrentBuyLotSize = BuyLimitLots + LotSizeAdd;
              }
            else
              {
               CurrentBuyLotSize = BuyLimitLots * LotSizeTimes;
              }

         // 计算卖单的手数

         if(SellLimitLots == 0)
           {
            CurrentSellLotSize = LotSize;
           }
         else
            if(SellLimitLots <= MaxLotSize)
              {
               CurrentSellLotSize = SellLimitLots + LotSizeAdd;
              }
            else
              {
               CurrentSellLotSize = SellLimitLots * LotSizeTimes;
              }

         // 如果有BuyLimit和SellLimit仓单 不操作
         bool hasBuyLimit = false;
         bool hasSellLimit = false;
         for(int i = OrdersTotal() - 1; i >= 0; i--)
           {
            if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
              {
               if(OrderMagicNumber() == MagicNumber && OrderType() == OP_BUYLIMIT)
                 {
                  hasBuyLimit = true;
                 }
               if(OrderMagicNumber() == MagicNumber && OrderType() == OP_SELLLIMIT)
                 {
                  hasSellLimit = true;
                 }
              }
           }

         // 如果没有BuyLimit或SellLimit仓单 补齐挂单
         if(!hasBuyLimit)  //如果没有BuyLimit仓单
           {
            int BuyTicket = OrderSend(Symbol(), OP_BUYLIMIT, CurrentBuyLotSize, BuyLimitPrice, 3, 0, 0, "BuyLimit", MagicNumber, 0, clrGreen);
            if(BuyTicket < 0)
              {
               Print("BuyLimit 下单失败: ", GetLastError());
              }
           }
         if(!hasSellLimit)  //如果没有SellLimit仓单
           {
            int SellTicket = OrderSend(Symbol(), OP_SELLLIMIT, CurrentSellLotSize, SellLimitPrice, 3, 0, 0, "SellLimit", MagicNumber, 0, clrRed);
            if(SellTicket < 0)
              {
               Print("SellLimit 下单失败: ", GetLastError());
              }
           }
        }
  }

//+------------------------------------------------------------------+
//+------------------------------------------------------------------+
