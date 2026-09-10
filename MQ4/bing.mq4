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

extern int Distance = 1300; // 限价距离（点）
extern double LotSize = 0.01; // 交易手数
extern double LotSizeAdd = 0.01; // 间隔递增(手)
extern double MaxLotSize = 0.03; // 间隔递增与倍数递增分界（手）
extern double LotSizeTimes = 1.5; // 倍数递增
extern double TargetProfit = 1.00; // 盈利目标（$）
extern int Slippage = 3; // 滑点
// extern int OrderDelayInSeconds = 2; // 延迟时间（秒）
extern int MagicNumber = 12345; // 魔术码

// 全局变量
datetime OrderOpenTime;
double BuyLimitLots = 0;
double SellLimitLots = 0;
double CurrentBuyLotSize = LotSize;
double CurrentSellLotSize = LotSize;
double TotalProfit = 0;
// double GetMaxBuyLots();
// double GetMaxSellLots();

//+------------------------------------------------------------------+
//| 初始化功能                                                       |
//+------------------------------------------------------------------+
int OnInit()
  {
// 初始化全局变量
   OrderOpenTime = TimeCurrent();
   CurrentBuyLotSize = LotSize;
   CurrentSellLotSize = LotSize;
   BuyLimitLots = 0;
   SellLimitLots = 0;
   TotalProfit = 0;

   return(INIT_SUCCEEDED);
  }

//+------------------------------------------------------------------+
//| 参数变动关闭所有仓单的初始化功能                                 |
//+------------------------------------------------------------------+
void OnDeinit(const int reason)
  {
// 关闭所有仓单
   CloseAllOrders();
  }

//+------------------------------------------------------------------+
//| 定义关闭所有仓单参数                                             |
//+------------------------------------------------------------------+

void CloseAllOrders()
  {
// 循环遍历所有订单
   for(int i = OrdersTotal() - 1; i >= 0; i--)
     {
      if(OrderSelect(i, SELECT_BY_POS))
        {
         // 检查订单是否属于该EA并且仍然开放
         if(OrderMagicNumber() == MagicNumber && OrderCloseTime() == 0)
           {
            // 根据订单类型关闭订单
            if(!OrderClose(OrderTicket(), OrderLots(), MarketInfo(OrderSymbol(), MODE_BID), 3, clrRed))
              {
               Print("OrderClose failed for order ", OrderTicket());
              }
           }
          else if(OrderType() == OP_BUYLIMIT || OrderType() == OP_SELLLIMIT || OrderType() == OP_BUYSTOP || OrderType() == OP_SELLSTOP)
              {
               if(!OrderDelete(OrderTicket()))
                 {
                  Print("OrderDelete failed for order ", OrderTicket());
                 }
              }
        }
     }
// 重置所有参数
   TotalProfit = 0;
   CurrentBuyLotSize = LotSize;
   CurrentSellLotSize = LotSize;
   BuyLimitLots = 0;
   SellLimitLots = 0;
   Sleep(2000); // 暂停交易2秒
   OrderOpenTime = TimeCurrent();
  }

// 定义哈希表结构体
/*struct OrderHash {
  int buyLots;
  int sellLots;
};

// 定义哈希表
OrderHash orderHash[100];

// 计算已成交的买入和卖出单的手数
void CalculateOrderHash() {
  int totalOrders = OrdersTotal();
  int first = 0;
  int last = totalOrders - 1;
  int middle = (first + last) / 2;
  while (first <= last) {
    if (OrderSelect(middle, SELECT_BY_POS, MODE_TRADES)) {
      if (OrderMagicNumber() == MagicNumber) {
        if (OrderType() == OP_BUY) {
          orderHash[OrderTicket()].buyLots += (int)OrderLots();
        } else if (OrderType() == OP_SELL) {
          orderHash[OrderTicket()].sellLots += (int)OrderLots();
        }
      }
      break;
    } else if (OrderSelect(middle, SELECT_BY_POS, MODE_TRADES) == false) {
      if (middle > totalOrders) {
        last = middle - 1;
      } else {
        first = middle + 1;
      }
      middle = (first + last) / 2;
    }
  }
}

// 计算新的买入和卖出单的手数
void CalculateNewLots() {
  CalculateOrderHash();
  double buyLots = 0;
  double sellLots = 0;
  for (int i = 0; i < ArraySize(orderHash); i++) {
    if (orderHash[i].buyLots > orderHash[i].sellLots) {
        buyLots += orderHash[i].buyLots - orderHash[i].sellLots;
    } else if (orderHash[i].buyLots < orderHash[i].sellLots) {
        sellLots += orderHash[i].sellLots - orderHash[i].buyLots;
    }
  }
  // 更新CurrentBuyLotSize和CurrentSellLotSize
  if (buyLots == 0 || sellLots == 0) 
    {
    CurrentBuyLotSize = LotSize;
    CurrentSellLotSize = LotSize;
    } 
    else 
      if (buyLots <= MaxLotSize || sellLots <= MaxLotSize) 
        {
          CurrentBuyLotSize = buyLots + LotSizeAdd;
          CurrentSellLotSize = sellLots + LotSizeAdd;
        } 
        else {
          CurrentBuyLotSize = buyLots * LotSizeTimes;
          CurrentSellLotSize = sellLots * LotSizeTimes;
        }
}*/

//+------------------------------------------------------------------+
//| 主逻辑功能                                                        |
//+------------------------------------------------------------------+
void OnTick()
  {
// 计算持仓总利润
   for(int i = OrdersTotal() - 1; i >= 0; i--)
     {
      if(OrderSelect(i, SELECT_BY_POS, MODE_TRADES))
        {
         TotalProfit += OrderProfit() + OrderCommission();
        }
     }
// 检查是否已经达到目标利润
   if(TotalProfit >= TargetProfit)
     {
      // 关闭所有订单并重置总利润
      CloseAllOrders();
      TotalProfit = 0;
     }
// 检查是否满足延迟时间
   /*if(TimeCurrent() - OrderDelayInSeconds >= OrderOpenTime)
     { */
// 计算BuyLimit 和 SellLimit 的价格
   double BuyLimitPrice = Ask - Distance * Point;
   double SellLimitPrice = Bid + Distance * Point;

 // 获取最大手数的已成交买单

   for(int i = 0; i < OrdersTotal(); i++)
     {
      if(OrderSelect(i, SELECT_BY_POS) && OrderType() == OP_BUY)
        {
         double lots = OrderLots();
         if(lots > BuyLimitLots)
            BuyLimitLots = lots;
        }
     }

// 获取最大手数的已成交卖单

   for(int i = 0; i < OrdersTotal(); i++)
     {
      if(OrderSelect(i, SELECT_BY_POS) && OrderType() == OP_SELL)
        {
         double lots = OrderLots();
         if(lots > SellLimitLots)
            SellLimitLots = lots;
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

// 如果有BuyLimit和SellLimit仓单，不操作
   bool hasBuyLimit = false;
   bool hasSellLimit = false;
   for(int i = OrdersTotal() - 1; i >= 0; i--)
     {
      if(OrderSelect(i, SELECT_BY_POS))
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

// 如果没有则执行BuyLimit和SellLimit仓单
   if(!hasBuyLimit)  //如果没有BuyLimit仓单
     {
      int BuyTicket = OrderSend(Symbol(), OP_BUYLIMIT, CurrentBuyLotSize, BuyLimitPrice, 3, 0, 0, "BuyLimit", MagicNumber, 0, clrGreen);
      if(BuyTicket < 0)
        {
         Print("Error placing BuyLimit order: ", GetLastError());
        }
     }
   if(!hasSellLimit)  //如果没有SellLimit仓单
     {
      int SellTicket = OrderSend(Symbol(), OP_SELLLIMIT, CurrentSellLotSize, SellLimitPrice, 3, 0, 0, "SellLimit", MagicNumber, 0, clrRed);
      if(SellTicket < 0)
        {
         Print("Error placing SellLimit order: ", GetLastError());
        }
     }

// 检查是否有仓单被关闭
   /* for(int i = OrdersTotal() - 1; i >= 0; i--)
     {
      if(OrderSelect(i, SELECT_BY_POS))
        {
         if(OrderMagicNumber() == MagicNumber && OrderCloseTime() != 0)
           {
            // 检查是否已经达到目标利润
            if(TotalProfit >= TargetProfit)
              {
               // 关闭所有订单并重置总利润
               CloseAllOrders();
               TotalProfit = 0;
               // OrderOpenTime = TimeCurrent();
               // 重置仓单手数
               CurrentBuyLotSize = LotSize;
               CurrentSellLotSize = LotSize;
              }
            else
              {
               // 执行新的BuyLimit和SellLimit仓单
               int BuyTicket = OrderSend(Symbol(), OP_BUYLIMIT, CurrentBuyLotSize, BuyLimitPrice, 3, 0, 0, "BuyLimit", MagicNumber, 0, clrGreen);
               int SellTicket = OrderSend(Symbol(), OP_SELLLIMIT, CurrentSellLotSize, SellLimitPrice, 3, 0, 0, "SellLimit", MagicNumber, 0, clrRed);

               // 检查仓单是否执行成功
               if(BuyTicket < 0 || SellTicket < 0)
                 {
                  Print("Error placing orders: ", GetLastError());
                 }
              }
           }
        }
     } */
   /* } */
  }

//+------------------------------------------------------------------+
