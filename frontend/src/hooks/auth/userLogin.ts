import { api } from "src/boot/axios";
import { ILogin, ILoggedUser } from "src/interfaces";
import { reactive, toRefs, Ref } from "vue";

interface IState {
  fetching: boolean;
  done: boolean;
  user: ILoggedUser;
  token: string;
  error: any;
}
const state = reactive<IState>({
  fetching: false,
  done: false,
  user: <ILoggedUser>{},
  token: "",
  error: {},
});
export default function useLogin() {
  const login: Function = async (body: ILogin): Promise<void> => {
    state.fetching = true;
    try {
      const res = await api.post("/auth/login", { ...body });
      if (res.status === 201) {
        const { user, token } = res.data;
        localStorage.setItem("token", token);
        localStorage.setItem("user", JSON.stringify(user));
        state.user = user;
        state.token = token;
        state.done = true;
        console.log("state=>", state);
      }
    } catch (error) {
      state.error = error;
    } finally {
      state.fetching = false;
    }
  };
  const isLoggedIn: Function = async () => {
    try {
      const token = <string | null>localStorage.getItem("token");
      if (token) {
        const user: ILoggedUser = JSON.parse(
          <string>localStorage.getItem("user")
        );
        state.done = true;
        state.token = token;
        state.user = user;
      } else {
        return state;
      }
    } catch (error) {
      state.error = error;
    }
  };
  const logout = async () => {
    localStorage.clear();
    state.done = false;
    state.error = {};
    state.fetching = false;
    state.token = "";
    state.user = <ILoggedUser>{};
  };
  return { isLoggedIn, login, logout, ...toRefs(state) };
}
