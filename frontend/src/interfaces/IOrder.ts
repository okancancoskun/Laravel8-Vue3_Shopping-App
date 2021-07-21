import { ICartItems } from "./ICart";
import { IProduct } from "./IProduct";
import { IUser } from "./IUser";

interface IOrderItems {
  id: number;
  productId: number;
  quantity: number;
  price: number;
  order_id: number;
  created_at: Date;
  updated_at: Date;
  product_id: IProduct;
}
export interface IOrder {
  id: number;
  userId: number;
  order_items: IOrderItems[];
  user_id: IUser;
  created_at: Date;
  updated_at: Date;
}
