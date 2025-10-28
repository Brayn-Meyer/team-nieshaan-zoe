import { io } from "socket.io-client";
import API_URL from "../API.js";

const socket = io(API_URL);

export default socket;