import { IProduct } from "./IProduct";

export interface ICartItems {
  id: number;
  productId: number;
  quantity: number;
  price: number;
  created_at: Date;
  updated_at: Date;
  product_id: IProduct;
}

export interface ICart {
  id: number;
  created_at: Date;
  updated_at: Date;
  cart_items: ICartItems[];
}
