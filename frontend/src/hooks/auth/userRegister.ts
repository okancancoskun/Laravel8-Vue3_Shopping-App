import { api } from "src/boot/axios";
import { IBaseState, IRegister } from "src/interfaces";
import { reactive } from "vue";

interface IState extends IBaseState<{}> {}
const state = reactive<IState>({
  fetching: false,
  fetched: false,
  data: {},
  error: <any>{},
});

export default function useRegister() {
  const register: (body: IRegister) => Promise<void> = async (
    body: IRegister
  ) => {
    state.fetching = true;
    try {
      const res = await api.post("/auth/register", { ...body });
      if (res.status === 201) {
        state.fetched = true;
      }
    } catch (error) {
      state.error = error;
    } finally {
      state.fetching = false;
    }
  };
  return { register, state };
}
