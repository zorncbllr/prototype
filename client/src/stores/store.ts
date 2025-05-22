import { create } from "zustand";

interface VoterStore {
  open: boolean;
  setOpen: (open: boolean) => void;
}

export const useVoterStore = create<VoterStore>((set) => ({
  open: false,
  setOpen: (open) => set(() => ({ open })),
}));
