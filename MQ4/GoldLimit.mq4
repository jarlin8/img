//+------------------------------------------------------------------+
//|                                                 复利说 GoldLimit |
//|                                           Copyright 2023, 复利说 |
//|                                              http://www.ssgg.net |
//+------------------------------------------------------------------+
#property link          "http://www.ssgg.net"
#property version       "1.08"
#property strict
#property copyright     "GoldLimit - 2023"
#property description   "SmartLimit黄金定制版.不要同时与其他EA混用或者手动下单干预！"

extern int Distance = 1300; // 限价距离（点）
extern double LotSize = 0.01; // 交易手数
extern double LotSizeAdd = 0.03; // 间隔递增(手)
extern double MaxLotSize = 0.07; // 间隔递增与倍数递增分界（手）
extern double LotSizeTimes = 1.8; // 倍数递增
extern double TargetProfit = 1.6; // 盈利目标（$）
//extern double stopLoss = 6000; // 止损点数
extern double FirsttakeProfit = 2000; // 首单止盈点数
extern double takeProfit = 1000; // 止盈点数
const int DELAY_MS = 100; // 延迟的毫秒数
extern int MagicNumber = 12345; // 魔术码

// 全局变量
datetime OrderOpenTime;
double CurrentBuyLotSize;
double CurrentSellLotSize;
double BuyLimitLots = 0;
double SellLimitLots = 0;

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
   PlaceBuyLimit();
   PlaceSellLimit();

   return(INIT_SUCCEEDED);
  }

// 定义关闭所有仓单参数

//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
void CloseAllOrders()
  {
   for(int i = OrdersTotal() - 1; i >= 0; i--)
     {
      if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
        {
         if(OrderMagicNumber() == MagicNumber && OrderCloseTime() == 0)
           {
            if(OrderType() == OP_BUY || OrderType() == OP_SELL)
              {
               if(OrderClose(OrderTicket(), OrderLots(), OrderClosePrice(), 3, Red))
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

// 下限价买单
void PlaceBuyLimit()
  {
    double TP = 0;
    double BuyLimitPrice = Ask - Distance * Point;
// 计算买单的手数
   if(BuyLimitLots == 0)
     {
      CurrentBuyLotSize = LotSize;
      TP = BuyLimitPrice + FirsttakeProfit * Point;
     }
   else
      if(BuyLimitLots < MaxLotSize)
        {
         CurrentBuyLotSize = BuyLimitLots + LotSizeAdd;
         TP = BuyLimitPrice + takeProfit * Point;
        }
      else
        {
         CurrentBuyLotSize = BuyLimitLots * LotSizeTimes;
         TP = BuyLimitPrice + takeProfit * Point;
        }

   //double SL = BuyLimitPrice - stopLoss * Point;
   //double TP = BuyLimitPrice + takeProfit * Point;
   int BuyTicket = OrderSend(Symbol(), OP_BUYLIMIT, CurrentBuyLotSize, BuyLimitPrice, 3, 0, TP, "GoldLimit", MagicNumber, 0, clrGreen);
   if(BuyTicket < 0)
     {
      Print("BuyLimit 下单失败: ", GetLastError());
     }
  }
//下限价卖单
void PlaceSellLimit()
  {
    double TP = 0;
    double SellLimitPrice = Bid + Distance * Point;
// 计算卖单的手数
   if(SellLimitLots == 0)
     {
      CurrentSellLotSize = LotSize;
      TP = SellLimitPrice - FirsttakeProfit * Point;
     }
   else
      if(SellLimitLots < MaxLotSize)
        {
         CurrentSellLotSize = SellLimitLots + LotSizeAdd;
         TP = SellLimitPrice - takeProfit * Point;
        }
      else
        {
         CurrentSellLotSize = SellLimitLots * LotSizeTimes;
         TP = SellLimitPrice - takeProfit * Point;
        }
   //double SL = SellLimitPrice + stopLoss * Point;
   //double TP = SellLimitPrice - takeProfit * Point;
   int SellTicket = OrderSend(Symbol(), OP_SELLLIMIT, CurrentSellLotSize, SellLimitPrice, 3, 0, TP, "GoldLimit", MagicNumber, 0, clrRed);
   if(SellTicket < 0)
     {
      Print("SellLimit 下单失败: ", GetLastError());
     }
  }

//+------------------------------------------------------------------+
//|  主逻辑功能                                                       |
//+------------------------------------------------------------------+
void OnTick()
  {

// 获取最大手数的已成交买单和卖单
   for(int i = 0; i < OrdersTotal(); i++)
     {
      if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
        {
         if(OrderMagicNumber() == MagicNumber && OrderType() == OP_BUY)
           {
            double lots = OrderLots();
            if(lots > BuyLimitLots)
              {
               BuyLimitLots = lots;
              }
           }
         else
            if(OrderMagicNumber() == MagicNumber && OrderType() == OP_SELL)
              {
               double lots = OrderLots();
               if(lots > SellLimitLots)
                 {
                  SellLimitLots = lots;
                 }
              }
        }
     }

// 计算持仓总利润 不用遍历所有订单
   double TotalProfit = AccountProfit();
// 检查是否已经达到目标利润
// 如果达成目标利润 平仓 继续挂单
   if(TotalProfit >= TargetProfit)
     {
      CloseAllOrders();
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
      PlaceBuyLimit();
      PlaceSellLimit();
     }

// 如果未达成目标利润 继续挂单
   else
      if(TotalProfit < TargetProfit)
        {
         // 如果有BuyLimit和SellLimit仓单 不操作
         bool hasBuyLimit = false;
         bool hasSellLimit = false;
         bool hasBuy = false;
         bool hasSell = false;
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
               if(OrderMagicNumber() == MagicNumber && OrderType() == OP_BUY)
                 {
                  hasBuy = true;
                 }
               if(OrderMagicNumber() == MagicNumber && OrderType() == OP_SELL)
                 {
                  hasSell = true;
                 }
              }
           }
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
         // 如果没有BuyLimit或SellLimit仓单 补齐挂单
         if(!hasBuyLimit)  //如果没有BuyLimit仓单
           {
            PlaceBuyLimit();
           }
         if(!hasSellLimit)  //如果没有SellLimit仓单
           {
            PlaceSellLimit();
           }
         //如果没有买卖单且限价单的手数也不相等 删除所有限价单并重置手数
         if(!hasBuy && !hasSell && (CurrentBuyLotSize != CurrentSellLotSize))
           {
            CloseAllOrders();
            CurrentBuyLotSize = LotSize;
            CurrentSellLotSize = LotSize;
            BuyLimitLots = 0;
            SellLimitLots = 0;
           }

        }
  }

//+------------------------------------------------------------------+
//+------------------------------------------------------------------+