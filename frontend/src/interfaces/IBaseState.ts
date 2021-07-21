export interface IBaseState<T> {
  fetching: boolean;
  fetched: boolean;
  data: T;
  error: any;
}
