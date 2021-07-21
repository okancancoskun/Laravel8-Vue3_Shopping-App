import { ICategory } from "./ICategory";

export interface IProduct {
  id: number;
  name: string;
  detail: string;
  price: number;
  image: any;
  category: ICategory;
}
