import { api } from "../../boot/axios";
import { IBaseState, ICategory } from "../../interfaces";
import { reactive, onBeforeMount, onMounted } from "vue";

interface IState extends IBaseState<ICategory[]> {}
interface IPostState extends IBaseState<ICategory> {}
type CategoryHooksType = {
  getCategories: () => Promise<void>;
  createCategory: (data: { name: string }) => Promise<void>;
  categoryState: IState;
  postCategoryState: IPostState;
};

const state = reactive<IState>({
  fetching: false,
  fetched: false,
  data: <ICategory[]>[],
  error: <any>{},
});

const postState = reactive<IPostState>({
  fetching: false,
  fetched: false,
  data: <ICategory>{},
  error: <any>{},
});

export default function useCategory(): CategoryHooksType {
  const getCategories: () => Promise<void> = async () => {
    state.fetching = true;
    try {
      const res = await api.get("/category");
      if (res.status === 200) {
        state.data = res.data as ICategory[];
        state.fetched = true;
      }
    } catch (error) {
      state.error = error;
    } finally {
      state.fetching = false;
    }
  };

  const createCategory: (data: { name: string }) => Promise<void> =
    async (data: { name: string }) => {
      postState.fetching = true;
      try {
        const res = await api({
          url: "category/create",
          method: "POST",
          data: { ...data },
        });
        if (res.status === 201) {
          postState.fetched = true;
        }
      } catch (error) {
        postState.error = error;
      } finally {
        postState.fetching = false;
      }
    };

  return {
    getCategories,
    createCategory,
    categoryState: state,
    postCategoryState: postState,
  };
}
