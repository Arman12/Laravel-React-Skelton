export function deepEqual<T>(obj1: T, obj2: T): boolean {
  if (obj1 === null || obj2 === null) {
    return obj1 === obj2;
  }

  if (typeof obj1 !== 'object' || typeof obj2 !== 'object') {
    return false;
  }

  const keys1 = Object.keys(obj1);
  const keys2 = Object.keys(obj2);

  if (keys1.length !== keys2.length) {
    return false;
  }

  for (const key of keys1) {
    if (!deepEqual((obj1 as any)[key], (obj2 as any)[key])) {
      return false;
    }
  }

  return true;
}

export function arraysEqual<T>(arr1: T[], arr2: T[]): boolean {
  if (arr1.length !== arr2.length) {
    return false;
  }

  for (let i = 0; i < arr1.length; i++) {
    if (arr1[i] !== arr2[i]) {
      return false;
    }
  }

  return true;
}

export function hasNonEmptyValues(obj: Record<string, any>): boolean {
  for (const key in obj) {
    if (obj.hasOwnProperty(key)) {
      const value = obj[key];
      if (value !== null && value !== undefined && value !== '') {
        return true;
      }
    }
  }
  return false;
}
