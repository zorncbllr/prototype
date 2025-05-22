export interface Voter {
  voterId: string;
  name: string;
  precinct: string;
  isGiven: boolean;
}

export interface SuccessResponse {
  message: string;
}

export interface ChangeStatusProps {
  voterId: string;
  value: boolean;
}
