n, k = [int(_) for _ in input().split()]

print(n*3 + min(abs(n-k), k-1))